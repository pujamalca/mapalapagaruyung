<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $indonesianNames = [
            'Budi Santoso', 'Ani Wijaya', 'Dedi Pratama', 'Siti Nurhaliza',
            'Eko Saputra', 'Rina Kusuma', 'Ahmad Fauzi', 'Dewi Lestari',
            'Rizki Ramadhan', 'Maya Sari', 'Fajar Nugroho', 'Linda Permata',
            'Hendra Gunawan', 'Putri Wulandari', 'Irfan Hakim', 'Ratna Dewi',
            'Bambang Wijaya', 'Indah Safitri', 'Tono Sugiarto', 'Ayu Lestari',
        ];

        $name = $indonesianNames[array_rand($indonesianNames)];
        $username = Str::slug(explode(' ', $name)[0]) . rand(100, 999);

        $majors = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi', 'Ilmu Komunikasi', 'Teknik Sipil'];
        $faculties = ['Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Ilmu Sosial dan Politik', 'Fakultas MIPA'];
        $bloodTypes = ['A', 'B', 'AB', 'O'];

        return [
            'name' => $name,
            'username' => $username,
            'email' => Str::slug($name) . rand(1, 999) . '@example.com',
            'email_verified_at' => now(),
            'phone' => fake()->optional()->numerify('08##-####-####'),
            'avatar' => fake()->optional()->imageUrl(200, 200, 'people'),
            'bio' => fake()->optional()->sentence(10),
            'is_active' => true,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            // Mapala-specific fields
            'nim' => fake()->optional()->numerify('##########'),
            'major' => fake()->optional()->randomElement($majors),
            'faculty' => fake()->optional()->randomElement($faculties),
            'enrollment_year' => fake()->optional()->numberBetween(2018, now()->year),
            'mapala_join_year' => fake()->optional()->numberBetween(2020, now()->year),
            'member_status' => fake()->randomElement(['prospective', 'junior', 'member', 'alumni']),
            'address' => fake()->optional()->address(),
            'blood_type' => fake()->optional()->randomElement($bloodTypes),
            'medical_history' => fake()->optional()->sentence(8),
            'emergency_contact' => [
                'name' => fake()->name(),
                'relationship' => fake()->randomElement(['Orang Tua', 'Saudara', 'Kerabat']),
                'phone' => fake()->numerify('08##-####-####'),
            ],
            'skills' => fake()->optional()->randomElements([
                ['skill' => 'Navigasi Darat', 'level' => 'Advanced', 'certified' => true],
                ['skill' => 'Rock Climbing', 'level' => 'Intermediate', 'certified' => false],
                ['skill' => 'P3K', 'level' => 'Basic', 'certified' => true],
                ['skill' => 'SAR', 'level' => 'Advanced', 'certified' => true],
            ], rand(1, 3)),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a prospective member.
     */
    public function prospective(): static
    {
        return $this->state(fn (array $attributes) => [
            'member_status' => 'prospective',
            'member_number' => null,
        ]);
    }

    /**
     * Indicate that the user is a junior member.
     */
    public function junior(): static
    {
        return $this->state(fn (array $attributes) => [
            'member_status' => 'junior',
            'member_number' => 'MAP-' . now()->year . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Indicate that the user is a full member.
     */
    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'member_status' => 'member',
            'member_number' => 'MAP-' . now()->year . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Indicate that the user is an alumni.
     */
    public function alumni(): static
    {
        return $this->state(fn (array $attributes) => [
            'member_status' => 'alumni',
            'member_number' => 'MAP-' . rand(2015, 2022) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Indicate that the user has complete Mapala profile.
     */
    public function withMapalaProfile(): static
    {
        return $this->state(fn (array $attributes) => [
            'nim' => fake()->numerify('##########'),
            'major' => 'Teknik Informatika',
            'faculty' => 'Fakultas Teknik',
            'enrollment_year' => now()->year - 2,
            'mapala_join_year' => now()->year - 1,
            'address' => fake()->address(),
            'blood_type' => fake()->randomElement(['A', 'B', 'AB', 'O']),
        ]);
    }
}
