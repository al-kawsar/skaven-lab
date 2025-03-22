<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class StudentFactory extends Factory
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
            'nis' => $this->faker->unique()->randomNumber(6),
            'nisn' => $this->faker->unique()->numberBetween(100000000000, 999999999999), // Memastikan 12 digit
            'alamat' => $this->faker->address,
            'jenis_kelamin' => $this->faker->randomElement(['l', 'p']),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'tgl_lahir' => $this->faker->date('d-m-Y', '-15 years'),
        ];
    }
}
