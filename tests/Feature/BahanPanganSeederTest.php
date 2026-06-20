<?php

use App\Models\BahanPangan;
use Database\Seeders\BahanPanganSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('seeder replaces bahan pangan with the expected categories', function () {
    BahanPangan::factory()->create(['nama' => 'Data Lama']);

    $this->seed(BahanPanganSeeder::class);
    $this->seed(BahanPanganSeeder::class);

    $expectedNames = [
        'Serealia',
        'Serealia Olahan',
        'Umbi Berpati',
        'Umbi Berpati Olahan',
        'Kacang & Biji-bijian',
        'Kacang & Biji-bijian Olahan',
        'Sayuran',
        'Sayuran Olahan',
        'Buah',
        'Buah Olahan',
        'Daging dan Unggas',
        'Daging Olahan',
        'Ikan, Kerang, Udang',
        'Ikan, Kerang, Udang Olahan',
        'Telur',
        'Telur Olahan',
        'Susu',
        'Susu Olahan',
        'Lemak dan Minyak',
        'Lemak, Minyak Olahan',
        'Gula, Sirup, Konfeksioneri',
        'Gula, Sirup, Olahan',
        'Bumbu',
        'Bumbu Olahan',
        'Minuman',
        'Minuman Olahan',
    ];

    expect(BahanPangan::query()->orderBy('id')->pluck('nama')->all())
        ->toBe($expectedNames)
        ->and(BahanPangan::query()->whereNull('deskripsi')->count())->toBe(0)
        ->and(BahanPangan::query()->where('deskripsi', '')->count())->toBe(0)
        ->and(BahanPangan::query()->whereNotNull('tkpi')->count())->toBe(0)
        ->and(BahanPangan::query()->whereNotNull('olahan')->count())->toBe(0);
});
