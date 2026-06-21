<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $divisis = [
            'Management',
            'Persiapan',
            'Pengolahan I',
            'Pengolahan II',
            'Pemorsian & Packing I',
            'Pemorsian & Packing II',
            'Antar Jemput',
            'Pengiriman',
            'Cuci Ompreng',
            'Cleaning Service I',
            'Cleaning Service II',
        ];

        foreach ($divisis as $divisi) {
            Divisi::firstOrCreate(['nama' => $divisi]);
        }
    }
}
