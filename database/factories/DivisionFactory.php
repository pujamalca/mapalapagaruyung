<?php

namespace Database\Factories;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Division>
 */
class DivisionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Division::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $divisions = [
            [
                'name' => 'Gunung & Rimba',
                'description' => 'Divisi yang fokus pada pendakian gunung dan eksplorasi hutan.',
                'icon' => '🏔️',
                'color' => '#10B981', // Green
            ],
            [
                'name' => 'Panjat Tebing',
                'description' => 'Divisi yang mengelola kegiatan rock climbing dan wall climbing.',
                'icon' => '🧗',
                'color' => '#F59E0B', // Amber
            ],
            [
                'name' => 'Penelusuran Gua',
                'description' => 'Divisi yang fokus pada eksplorasi dan penelusuran gua.',
                'icon' => '🕳️',
                'color' => '#6B7280', // Gray
            ],
            [
                'name' => 'Arung Jeram',
                'description' => 'Divisi yang mengelola kegiatan rafting dan river crossing.',
                'icon' => '🚣',
                'color' => '#3B82F6', // Blue
            ],
            [
                'name' => 'Konservasi Alam',
                'description' => 'Divisi yang fokus pada pelestarian lingkungan dan edukasi.',
                'icon' => '🌿',
                'color' => '#059669', // Emerald
            ],
        ];

        $division = fake()->randomElement($divisions);

        return [
            'name' => $division['name'],
            'description' => $division['description'],
            'icon' => $division['icon'],
            'color' => $division['color'],
            'head_id' => null, // Will be set manually
            'work_program' => fake()->optional()->paragraph(3),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the division is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the division is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set a head for the division.
     */
    public function withHead(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'head_id' => $user->id,
        ]);
    }
}
