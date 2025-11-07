<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DivisionCrudTest extends TestCase
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
     * Test admin can create division.
     */
    public function test_admin_can_create_division(): void
    {
        $this->actingAs($this->admin);

        $divisionData = [
            'name' => 'Gunung & Rimba',
            'description' => 'Divisi yang fokus pada pendakian gunung',
            'icon' => '🏔️',
            'color' => '#10B981',
            'is_active' => true,
            'sort_order' => 1,
        ];

        $division = Division::create($divisionData);

        $this->assertDatabaseHas('divisions', [
            'name' => 'Gunung & Rimba',
            'is_active' => true,
        ]);

        $this->assertEquals('Gunung & Rimba', $division->name);
        $this->assertNotNull($division->slug);
    }

    /**
     * Test admin can update division.
     */
    public function test_admin_can_update_division(): void
    {
        $this->actingAs($this->admin);

        $division = Division::factory()->create([
            'name' => 'Panjat Tebing',
        ]);

        $division->update([
            'name' => 'Rock Climbing',
            'description' => 'Updated description',
        ]);

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'name' => 'Rock Climbing',
            'description' => 'Updated description',
        ]);
    }

    /**
     * Test admin can delete division.
     */
    public function test_admin_can_delete_division(): void
    {
        $this->actingAs($this->admin);

        $division = Division::factory()->create();

        $division->delete();

        $this->assertDatabaseMissing('divisions', [
            'id' => $division->id,
        ]);
    }

    /**
     * Test admin can assign members to division.
     */
    public function test_admin_can_assign_members_to_division(): void
    {
        $this->actingAs($this->admin);

        $division = Division::factory()->create();
        $user = User::factory()->create();

        $division->members()->attach($user->id, [
            'joined_at' => now(),
            'role' => 'Anggota',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('division_user', [
            'division_id' => $division->id,
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        $this->assertTrue($division->members->contains($user));
    }

    /**
     * Test admin can set division head.
     */
    public function test_admin_can_set_division_head(): void
    {
        $this->actingAs($this->admin);

        $head = User::factory()->create();
        $division = Division::factory()->create([
            'head_id' => $head->id,
        ]);

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'head_id' => $head->id,
        ]);

        $this->assertEquals($head->id, $division->head->id);
    }

    /**
     * Test can filter active divisions.
     */
    public function test_can_filter_active_divisions(): void
    {
        Division::factory()->count(3)->active()->create();
        Division::factory()->count(2)->inactive()->create();

        $activeDivisions = Division::active()->get();

        $this->assertCount(3, $activeDivisions);
    }

    /**
     * Test can order divisions.
     */
    public function test_can_order_divisions(): void
    {
        Division::factory()->create(['sort_order' => 3]);
        Division::factory()->create(['sort_order' => 1]);
        Division::factory()->create(['sort_order' => 2]);

        $divisions = Division::ordered()->get();

        $this->assertEquals(1, $divisions->first()->sort_order);
        $this->assertEquals(3, $divisions->last()->sort_order);
    }

    /**
     * Test division slug is unique.
     */
    public function test_division_slug_is_unique(): void
    {
        Division::factory()->create(['name' => 'Gunung']);
        $division2 = Division::factory()->create(['name' => 'Gunung']);

        $this->assertEquals('gunung', Division::first()->slug);
        $this->assertNotEquals('gunung', $division2->slug);
        $this->assertStringStartsWith('gunung-', $division2->slug);
    }

    /**
     * Test removing member from division.
     */
    public function test_can_remove_member_from_division(): void
    {
        $division = Division::factory()->create();
        $user = User::factory()->create();

        $division->members()->attach($user->id, ['is_active' => true]);

        $this->assertDatabaseHas('division_user', [
            'division_id' => $division->id,
            'user_id' => $user->id,
        ]);

        $division->members()->detach($user->id);

        $this->assertDatabaseMissing('division_user', [
            'division_id' => $division->id,
            'user_id' => $user->id,
        ]);
    }
}
