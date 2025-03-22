<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lab>
 */
class LabFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'facilities' => $this->faker->sentence(5),
            'thumbnail' => \App\Models\File::inRandomOrder()->first()->id ?? 1, // Gunakan ID default atau 1 jika tidak ada file
            'status' => $this->faker->randomElement(['tersedia', 'tidak tersedia']),
        ];
    }
}
