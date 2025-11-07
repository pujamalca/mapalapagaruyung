<?php

namespace Database\Factories;

use App\Models\Cohort;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cohort>
 */
class CohortFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Cohort::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2015, now()->year);
        $romanNumerals = [
            'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X',
            'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX',
            'XXI', 'XXII', 'XXIII', 'XXIV', 'XXV', 'XXVI', 'XXVII', 'XXVIII', 'XXIX', 'XXX',
        ];

        $number = fake()->numberBetween(1, 30);

        return [
            'name' => 'Kader ' . $romanNumerals[$number - 1],
            'year' => $year,
            'theme' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['active', 'alumni']),
            'member_count' => fake()->numberBetween(10, 50),
            'sort_order' => $number,
        ];
    }

    /**
     * Indicate that the cohort is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'year' => now()->year,
        ]);
    }

    /**
     * Indicate that the cohort is alumni.
     */
    public function alumni(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'alumni',
            'year' => fake()->numberBetween(2010, now()->year - 2),
        ]);
    }
}
