<?php

use App\Models\BahanPangan;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('public bahan pangan catalog renders item details and loads alpine runtime', function () {
    BahanPangan::factory()->create([
        'nama' => 'Umbi Berpati Olahan',
        'deskripsi' => 'Produk umbi yang diolah.',
        'tkpi' => 'A.010',
        'olahan' => 'Keripik singkong',
    ]);

    $response = $this->get(route('katalog.bahan-pangan'));

    $response
        ->assertOk()
        ->assertSee('Umbi Berpati Olahan')
        ->assertSee('Produk umbi yang diolah.')
        ->assertSee('Keripik singkong')
        ->assertSee('<!-- Livewire Scripts -->', false)
        ->assertSee('data-update-uri=', false)
        ->assertSee('display: none !important;', false);
});
