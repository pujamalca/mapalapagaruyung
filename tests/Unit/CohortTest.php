<?php

namespace Tests\Unit;

use App\Models\Cohort;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohortTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test cohort can be created.
     */
    public function test_cohort_can_be_created(): void
    {
        $cohort = Cohort::factory()->create([
            'name' => 'Kader XX',
            'year' => 2024,
            'theme' => 'Bersatu dalam Keberagaman',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('cohorts', [
            'name' => 'Kader XX',
            'year' => 2024,
            'theme' => 'Bersatu dalam Keberagaman',
            'status' => 'active',
        ]);

        $this->assertEquals('Kader XX', $cohort->name);
        $this->assertEquals(2024, $cohort->year);
    }

    /**
     * Test cohort has correct relationships.
     */
    public function test_cohort_has_members_relationship(): void
    {
        $cohort = Cohort::factory()->create();

        // Create users belonging to this cohort (will be added after User migration extends)
        // This test will work after FASE 3A

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $cohort->members());
    }

    /**
     * Test active scope.
     */
    public function test_active_scope_returns_only_active_cohorts(): void
    {
        Cohort::factory()->create(['status' => 'active']);
        Cohort::factory()->create(['status' => 'active']);
        Cohort::factory()->create(['status' => 'alumni']);

        $activeCohorts = Cohort::active()->get();

        $this->assertCount(2, $activeCohorts);
        $this->assertTrue($activeCohorts->every(fn ($cohort) => $cohort->status === 'active'));
    }

    /**
     * Test alumni scope.
     */
    public function test_alumni_scope_returns_only_alumni_cohorts(): void
    {
        Cohort::factory()->create(['status' => 'active']);
        Cohort::factory()->create(['status' => 'alumni']);
        Cohort::factory()->create(['status' => 'alumni']);

        $alumniCohorts = Cohort::alumni()->get();

        $this->assertCount(2, $alumniCohorts);
        $this->assertTrue($alumniCohorts->every(fn ($cohort) => $cohort->status === 'alumni'));
    }

    /**
     * Test order by year scope.
     */
    public function test_order_by_year_scope_orders_correctly(): void
    {
        Cohort::factory()->create(['year' => 2022]);
        Cohort::factory()->create(['year' => 2024]);
        Cohort::factory()->create(['year' => 2023]);

        $cohortsDesc = Cohort::orderByYear()->get();
        $this->assertEquals(2024, $cohortsDesc->first()->year);
        $this->assertEquals(2022, $cohortsDesc->last()->year);

        $cohortsAsc = Cohort::orderByYear('asc')->get();
        $this->assertEquals(2022, $cohortsAsc->first()->year);
        $this->assertEquals(2024, $cohortsAsc->last()->year);
    }

    /**
     * Test full name attribute.
     */
    public function test_full_name_attribute_combines_name_and_year(): void
    {
        $cohort = Cohort::factory()->create([
            'name' => 'Kader XXI',
            'year' => 2024,
        ]);

        $this->assertEquals('Kader XXI (2024)', $cohort->full_name);
    }

    /**
     * Test cohort can have media.
     */
    public function test_cohort_can_have_media(): void
    {
        $cohort = Cohort::factory()->create();

        $this->assertInstanceOf(\Spatie\MediaLibrary\HasMedia::class, $cohort);
    }

    /**
     * Test member count default value.
     */
    public function test_member_count_has_default_value(): void
    {
        $cohort = Cohort::factory()->create(['member_count' => null]);

        $this->assertEquals(0, $cohort->member_count);
    }
}
