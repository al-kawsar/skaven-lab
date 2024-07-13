<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guru>
 */
class GuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'name' => $this->faker->name,
            'nip' => $this->faker->unique()->randomNumber(6),
            'alamat' => $this->faker->address,
            'jenis_kelamin' => $this->faker->randomElement(['l', 'p']),
            'tgl_lahir' => $this->faker->date('d-m-Y', '-30 years'),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
        ];
    }
}
