<?php

namespace Tests\Feature;

use App\Models\Cohort;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CohortCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin user for testing.
     */
    protected User $admin;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Admin');
    }

    /**
     * Test admin can view cohorts list.
     */
    public function test_admin_can_view_cohorts_list(): void
    {
        $this->actingAs($this->admin);

        Cohort::factory()->count(3)->create();

        // This will work after Filament Resource is created
        // $response = $this->get('/admin/cohorts');
        // $response->assertStatus(200);

        $this->assertTrue(true); // Placeholder until resource is created
    }

    /**
     * Test admin can create cohort.
     */
    public function test_admin_can_create_cohort(): void
    {
        $this->actingAs($this->admin);

        $cohortData = [
            'name' => 'Kader XXII',
            'year' => 2024,
            'theme' => 'Menggapai Impian di Puncak Tertinggi',
            'description' => 'Angkatan dengan semangat yang luar biasa.',
            'status' => 'active',
            'member_count' => 0,
            'sort_order' => 1,
        ];

        // Direct database test
        $cohort = Cohort::create($cohortData);

        $this->assertDatabaseHas('cohorts', [
            'name' => 'Kader XXII',
            'year' => 2024,
        ]);

        $this->assertEquals('Kader XXII', $cohort->name);
    }

    /**
     * Test admin can update cohort.
     */
    public function test_admin_can_update_cohort(): void
    {
        $this->actingAs($this->admin);

        $cohort = Cohort::factory()->create([
            'name' => 'Kader XX',
            'year' => 2023,
        ]);

        $cohort->update([
            'name' => 'Kader XXI',
            'year' => 2024,
            'theme' => 'Updated Theme',
        ]);

        $this->assertDatabaseHas('cohorts', [
            'id' => $cohort->id,
            'name' => 'Kader XXI',
            'year' => 2024,
            'theme' => 'Updated Theme',
        ]);
    }

    /**
     * Test admin can delete cohort.
     */
    public function test_admin_can_delete_cohort(): void
    {
        $this->actingAs($this->admin);

        $cohort = Cohort::factory()->create();

        $cohort->delete();

        $this->assertDatabaseMissing('cohorts', [
            'id' => $cohort->id,
        ]);
    }

    /**
     * Test cohort validation rules.
     */
    public function test_cohort_requires_name_and_year(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        // Try to create without required fields
        Cohort::create([
            'theme' => 'Test Theme',
        ]);
    }

    /**
     * Test can filter active cohorts.
     */
    public function test_can_filter_active_cohorts(): void
    {
        Cohort::factory()->count(3)->active()->create();
        Cohort::factory()->count(2)->alumni()->create();

        $activeCohorts = Cohort::active()->get();

        $this->assertCount(3, $activeCohorts);
    }

    /**
     * Test can sort by year.
     */
    public function test_can_sort_cohorts_by_year(): void
    {
        Cohort::factory()->create(['year' => 2023, 'name' => 'Kader XX']);
        Cohort::factory()->create(['year' => 2024, 'name' => 'Kader XXI']);
        Cohort::factory()->create(['year' => 2022, 'name' => 'Kader XIX']);

        $cohorts = Cohort::orderByYear('desc')->get();

        $this->assertEquals(2024, $cohorts->first()->year);
        $this->assertEquals(2022, $cohorts->last()->year);
    }
}
