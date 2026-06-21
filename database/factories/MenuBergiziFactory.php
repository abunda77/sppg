<?php

namespace Database\Factories;

use App\Models\MenuBergizi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuBergizi>
 */
class MenuBergiziFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->words(2, true),
            'deskripsi' => fake()->sentence(),
            'image' => null,
        ];
    }
}
