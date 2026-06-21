<?php

namespace Database\Factories;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Karyawan>
 */
class KaryawanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'jabatan_id' => Jabatan::inRandomOrder()->value('id'),
            'divisi_id' => Divisi::inRandomOrder()->value('id'),
            'no_telp' => fake()->numerify('08##########'),
        ];
    }
}
