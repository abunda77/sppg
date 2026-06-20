<?php

namespace Database\Factories;

use App\Models\BahanPangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BahanPangan>
 */
class BahanPanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'tkpi' => fake()->randomElement(['A1', 'B2', 'C3', 'D4']),
            'olahan' => fake()->randomElement(['Mentah', 'Rebus', 'Goreng', 'Kukus', 'Panggang']),
            'image' => null,
        ];
    }
}
