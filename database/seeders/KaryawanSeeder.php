<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Karyawan::factory(30)->create();
    }
}
