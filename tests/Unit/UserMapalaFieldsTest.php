<?php

namespace Tests\Unit;

use App\Models\Cohort;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMapalaFieldsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can have Mapala-specific fields.
     */
    public function test_user_can_have_mapala_fields(): void
    {
        $cohort = Cohort::factory()->create();

        $user = User::factory()->create([
            'nim' => '2024010001',
            'major' => 'Teknik Informatika',
            'faculty' => 'Fakultas Teknik',
            'enrollment_year' => 2024,
            'cohort_id' => $cohort->id,
            'member_number' => 'MAP-2024-001',
            'mapala_join_year' => 2024,
            'member_status' => 'member',
            'address' => 'Jl. Test No. 123',
            'blood_type' => 'A',
        ]);

        $this->assertEquals('2024010001', $user->nim);
        $this->assertEquals('Teknik Informatika', $user->major);
        $this->assertEquals('MAP-2024-001', $user->member_number);
        $this->assertEquals('member', $user->member_status);
    }

    /**
     * Test user belongs to cohort.
     */
    public function test_user_belongs_to_cohort(): void
    {
        $cohort = Cohort::factory()->create(['name' => 'Kader XX']);
        $user = User::factory()->create(['cohort_id' => $cohort->id]);

        $this->assertInstanceOf(Cohort::class, $user->cohort);
        $this->assertEquals('Kader XX', $user->cohort->name);
    }

    /**
     * Test emergency contact JSON field.
     */
    public function test_emergency_contact_is_stored_as_json(): void
    {
        $user = User::factory()->create([
            'emergency_contact' => [
                'name' => 'Budi Santoso',
                'relationship' => 'Ayah',
                'phone' => '0812-3456-7890',
            ],
        ]);

        $this->assertIsArray($user->emergency_contact);
        $this->assertEquals('Budi Santoso', $user->emergency_contact['name']);
        $this->assertEquals('Ayah', $user->emergency_contact['relationship']);
    }

    /**
     * Test skills JSON field.
     */
    public function test_skills_are_stored_as_json(): void
    {
        $user = User::factory()->create([
            'skills' => [
                ['skill' => 'Navigasi', 'level' => 'Advanced', 'certified' => true],
                ['skill' => 'P3K', 'level' => 'Basic', 'certified' => false],
            ],
        ]);

        $this->assertIsArray($user->skills);
        $this->assertCount(2, $user->skills);
        $this->assertEquals('Navigasi', $user->skills[0]['skill']);
    }

    /**
     * Test isMember helper method.
     */
    public function test_is_member_returns_correct_value(): void
    {
        $prospective = User::factory()->prospective()->create();
        $junior = User::factory()->junior()->create();
        $member = User::factory()->member()->create();
        $alumni = User::factory()->alumni()->create();

        $this->assertFalse($prospective->isMember());
        $this->assertTrue($junior->isMember());
        $this->assertTrue($member->isMember());
        $this->assertTrue($alumni->isMember());
    }

    /**
     * Test isActiveMember helper method.
     */
    public function test_is_active_member_returns_correct_value(): void
    {
        $prospective = User::factory()->prospective()->create(['is_active' => true]);
        $junior = User::factory()->junior()->create(['is_active' => true]);
        $member = User::factory()->member()->create(['is_active' => true]);
        $alumni = User::factory()->alumni()->create(['is_active' => true]);
        $inactive = User::factory()->member()->create(['is_active' => false]);

        $this->assertFalse($prospective->isActiveMember());
        $this->assertTrue($junior->isActiveMember());
        $this->assertTrue($member->isActiveMember());
        $this->assertFalse($alumni->isActiveMember());
        $this->assertFalse($inactive->isActiveMember());
    }

    /**
     * Test isAlumni helper method.
     */
    public function test_is_alumni_returns_correct_value(): void
    {
        $member = User::factory()->member()->create();
        $alumni = User::factory()->alumni()->create();

        $this->assertFalse($member->isAlumni());
        $this->assertTrue($alumni->isAlumni());
    }

    /**
     * Test isProspective helper method.
     */
    public function test_is_prospective_returns_correct_value(): void
    {
        $prospective = User::factory()->prospective()->create();
        $member = User::factory()->member()->create();

        $this->assertTrue($prospective->isProspective());
        $this->assertFalse($member->isProspective());
    }

    /**
     * Test member status label attribute.
     */
    public function test_member_status_label_returns_indonesian_text(): void
    {
        $prospective = User::factory()->create(['member_status' => 'prospective']);
        $junior = User::factory()->create(['member_status' => 'junior']);
        $member = User::factory()->create(['member_status' => 'member']);
        $alumni = User::factory()->create(['member_status' => 'alumni']);

        $this->assertEquals('Calon Anggota', $prospective->member_status_label);
        $this->assertEquals('Anggota Muda', $junior->member_status_label);
        $this->assertEquals('Anggota', $member->member_status_label);
        $this->assertEquals('Alumni', $alumni->member_status_label);
    }

    /**
     * Test emergency contact name attribute.
     */
    public function test_emergency_contact_name_attribute(): void
    {
        $user = User::factory()->create([
            'emergency_contact' => [
                'name' => 'Test Name',
                'relationship' => 'Test Relation',
                'phone' => '08123456789',
            ],
        ]);

        $this->assertEquals('Test Name', $user->emergency_contact_name);
        $this->assertEquals('08123456789', $user->emergency_contact_phone);
    }

    /**
     * Test user can have certificates media collection.
     */
    public function test_user_can_have_certificates_collection(): void
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->getMedia('certificates'));
    }

    /**
     * Test factory states for different member types.
     */
    public function test_factory_states_work_correctly(): void
    {
        $prospective = User::factory()->prospective()->create();
        $junior = User::factory()->junior()->create();
        $member = User::factory()->member()->create();
        $alumni = User::factory()->alumni()->create();

        $this->assertEquals('prospective', $prospective->member_status);
        $this->assertEquals('junior', $junior->member_status);
        $this->assertEquals('member', $member->member_status);
        $this->assertEquals('alumni', $alumni->member_status);

        $this->assertNull($prospective->member_number);
        $this->assertNotNull($junior->member_number);
        $this->assertStringStartsWith('MAP-', $junior->member_number);
    }
}
