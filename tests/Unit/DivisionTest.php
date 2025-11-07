<?php

namespace Tests\Unit;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DivisionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test division can be created.
     */
    public function test_division_can_be_created(): void
    {
        $division = Division::factory()->create([
            'name' => 'Gunung & Rimba',
            'description' => 'Divisi pendakian gunung',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('divisions', [
            'name' => 'Gunung & Rimba',
            'is_active' => true,
        ]);

        $this->assertEquals('Gunung & Rimba', $division->name);
    }

    /**
     * Test division slug is auto-generated.
     */
    public function test_division_slug_is_auto_generated(): void
    {
        $division = Division::factory()->create([
            'name' => 'Panjat Tebing',
        ]);

        $this->assertNotNull($division->slug);
        $this->assertEquals('panjat-tebing', $division->slug);
    }

    /**
     * Test division has correct relationships.
     */
    public function test_division_has_head_relationship(): void
    {
        $user = User::factory()->create();
        $division = Division::factory()->create([
            'head_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $division->head);
        $this->assertEquals($user->id, $division->head->id);
    }

    /**
     * Test division has members relationship.
     */
    public function test_division_has_members_relationship(): void
    {
        $division = Division::factory()->create();
        $users = User::factory()->count(3)->create();

        // Attach users to division
        foreach ($users as $user) {
            $division->members()->attach($user->id, [
                'joined_at' => now(),
                'role' => 'Anggota',
                'is_active' => true,
            ]);
        }

        $this->assertCount(3, $division->members);
        $this->assertInstanceOf(User::class, $division->members->first());
    }

    /**
     * Test active scope.
     */
    public function test_active_scope_returns_only_active_divisions(): void
    {
        Division::factory()->create(['is_active' => true]);
        Division::factory()->create(['is_active' => true]);
        Division::factory()->create(['is_active' => false]);

        $activeDivisions = Division::active()->get();

        $this->assertCount(2, $activeDivisions);
        $this->assertTrue($activeDivisions->every(fn ($division) => $division->is_active === true));
    }

    /**
     * Test ordered scope.
     */
    public function test_ordered_scope_orders_by_sort_order(): void
    {
        Division::factory()->create(['sort_order' => 3, 'name' => 'C']);
        Division::factory()->create(['sort_order' => 1, 'name' => 'A']);
        Division::factory()->create(['sort_order' => 2, 'name' => 'B']);

        $divisions = Division::ordered()->get();

        $this->assertEquals(1, $divisions->first()->sort_order);
        $this->assertEquals(3, $divisions->last()->sort_order);
    }

    /**
     * Test division can have media.
     */
    public function test_division_can_have_media(): void
    {
        $division = Division::factory()->create();

        $this->assertInstanceOf(\Spatie\MediaLibrary\HasMedia::class, $division);
    }

    /**
     * Test active member count attribute.
     */
    public function test_active_member_count_attribute(): void
    {
        $division = Division::factory()->create();
        $users = User::factory()->count(5)->create();

        // Attach 3 active and 2 inactive members
        foreach ($users->take(3) as $user) {
            $division->members()->attach($user->id, ['is_active' => true]);
        }

        foreach ($users->skip(3) as $user) {
            $division->members()->attach($user->id, ['is_active' => false]);
        }

        $division = $division->fresh();

        $this->assertEquals(3, $division->active_member_count);
        $this->assertEquals(5, $division->total_member_count);
    }

    /**
     * Test user can belong to multiple divisions.
     */
    public function test_user_can_belong_to_multiple_divisions(): void
    {
        $user = User::factory()->create();
        $divisions = Division::factory()->count(3)->create();

        foreach ($divisions as $division) {
            $division->members()->attach($user->id, [
                'joined_at' => now(),
                'role' => 'Anggota',
                'is_active' => true,
            ]);
        }

        $this->assertCount(3, $user->divisions);
    }
}
