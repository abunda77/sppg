<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $jabatans = [
            'Kepala SPPG',
            'Pengawas Gizi',
            'Pengawas Keuangan',
            'Kepala Lapangan',
            'Kepala Juru Masak',
            'Juru Masak',
            'Persiapan',
            'Pemorsian',
            'Pengemudi',
            'Kebersihan',
            'Keamanan',
            'Cuci Ompreng',
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::firstOrCreate(['nama' => $jabatan]);
        }
    }
}
