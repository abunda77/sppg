<?php

use App\Models\BahanPangan;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('guests are redirected to the login page', function () {
    auth()->logout();

    $response = $this->get(route('bahan-pangan.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit bahan pangan page', function () {
    $response = $this->get(route('bahan-pangan.index'));
    $response->assertOk();
});

test('bahan pangan page displays data', function () {
    BahanPangan::factory()->create(['nama' => 'Beras Putih']);

    Livewire::test('pages::bahan-pangan.index')
        ->assertSee('Beras Putih');
});

test('can search bahan pangan', function () {
    BahanPangan::factory()->create(['nama' => 'Beras Putih']);
    BahanPangan::factory()->create(['nama' => 'Jagung Kuning']);

    Livewire::test('pages::bahan-pangan.index')
        ->set('search', 'Beras')
        ->assertSee('Beras Putih')
        ->assertDontSee('Jagung Kuning');
});

test('can create bahan pangan', function () {
    Livewire::test('pages::bahan-pangan.index')
        ->call('openCreateModal')
        ->assertSet('showCreateModal', true)
        ->set('createNama', 'Beras Merah')
        ->set('createDeskripsi', 'Beras merah organik')
        ->set('createTkpi', 'A.002')
        ->set('createOlahan', 'Nasi Merah')
        ->call('createBahanPangan');

    expect(BahanPangan::where('nama', 'Beras Merah')->exists())->toBeTrue();
});

test('create bahan pangan requires nama', function () {
    Livewire::test('pages::bahan-pangan.index')
        ->call('openCreateModal')
        ->set('createNama', '')
        ->call('createBahanPangan')
        ->assertHasErrors(['createNama' => 'required']);
});

test('can create bahan pangan with image', function () {
    Storage::fake('public');

    Livewire::test('pages::bahan-pangan.index')
        ->call('openCreateModal')
        ->set('createNama', 'Kentang')
        ->set('createImage', UploadedFile::fake()->image('kentang.jpg'))
        ->call('createBahanPangan');

    $item = BahanPangan::where('nama', 'Kentang')->first();
    expect($item)->not->toBeNull();
    expect($item->image)->not->toBeNull();
    Storage::disk('public')->assertExists($item->image);
});

test('can edit bahan pangan', function () {
    $item = BahanPangan::factory()->create(['nama' => 'Beras Putih']);

    Livewire::test('pages::bahan-pangan.index')
        ->call('editBahanPangan', $item->id)
        ->assertSet('showEditModal', true)
        ->assertSet('editNama', 'Beras Putih')
        ->set('editNama', 'Beras Putih Premium')
        ->call('updateBahanPangan');

    expect($item->fresh()->nama)->toBe('Beras Putih Premium');
});

test('can delete bahan pangan', function () {
    $item = BahanPangan::factory()->create(['nama' => 'Beras Hapus']);

    Livewire::test('pages::bahan-pangan.index')
        ->call('deleteBahanPangan', $item->id);

    expect(BahanPangan::find($item->id))->toBeNull();
});

test('deleting bahan pangan with image removes the file', function () {
    Storage::fake('public');

    $path = UploadedFile::fake()->image('test.jpg')->store('bahan-pangan', 'public');
    $item = BahanPangan::factory()->create(['nama' => 'Dengan Gambar', 'image' => $path]);

    Livewire::test('pages::bahan-pangan.index')
        ->call('deleteBahanPangan', $item->id);

    Storage::disk('public')->assertMissing($path);
});

test('can import csv by updating existing names and creating new names', function () {
    BahanPangan::factory()->create([
        'nama' => 'Serealia',
        'deskripsi' => 'Deskripsi lama',
        'tkpi' => null,
        'olahan' => null,
    ]);

    $csv = implode("\n", [
        'nama,deskripsi,tkpi,olahan',
        'Serealia,"Deskripsi baru",A.001,"Nasi, Bubur"',
        'Buah,"Buah segar",F.001,Jus',
    ]);

    Livewire::test('pages::bahan-pangan.index')
        ->set('importFile', UploadedFile::fake()->createWithContent('bahan-pangan.csv', $csv))
        ->call('importCsv')
        ->assertHasNoErrors()
        ->assertSet('showImportModal', false);

    expect(BahanPangan::query()->where('nama', 'Serealia')->first())
        ->deskripsi->toBe('Deskripsi baru')
        ->tkpi->toBe('A.001')
        ->olahan->toBe('Nasi, Bubur')
        ->and(BahanPangan::query()->where('nama', 'Serealia')->count())->toBe(1)
        ->and(BahanPangan::query()->where('nama', 'Buah')->exists())->toBeTrue();
});

test('invalid csv import does not change any data', function () {
    $item = BahanPangan::factory()->create([
        'nama' => 'Serealia',
        'deskripsi' => 'Tetap sama',
    ]);

    $csv = implode("\n", [
        'nama,deskripsi,tkpi,olahan',
        'Serealia,"Deskripsi yang tidak boleh tersimpan",A.001,Nasi',
        ',"Nama kosong",A.001,Nasi',
    ]);

    Livewire::test('pages::bahan-pangan.index')
        ->set('importFile', UploadedFile::fake()->createWithContent('bahan-pangan.csv', $csv))
        ->call('importCsv')
        ->assertHasErrors(['importFile']);

    expect($item->fresh()->deskripsi)->toBe('Tetap sama')
        ->and(BahanPangan::query()->count())->toBe(1);
});

test('can export filtered bahan pangan to csv', function () {
    BahanPangan::factory()->create([
        'nama' => 'Serealia',
        'deskripsi' => 'Sumber karbohidrat',
        'tkpi' => 'A.001',
        'olahan' => 'Nasi',
    ]);
    BahanPangan::factory()->create(['nama' => 'Buah']);

    Livewire::test('pages::bahan-pangan.index')
        ->set('search', 'Serealia')
        ->call('exportCsv')
        ->assertFileDownloaded('bahan-pangan.csv', "nama,deskripsi,tkpi,olahan\nSerealia,\"Sumber karbohidrat\",A.001,Nasi\n");
});

test('can select all items on the current page', function () {
    BahanPangan::factory()->count(12)->create();

    Livewire::test('pages::bahan-pangan.index')
        ->call('togglePageSelection')
        ->assertCount('selectedIds', 10);
});

test('can bulk delete selected items and their images', function () {
    Storage::fake('public');

    $firstPath = UploadedFile::fake()->image('first.jpg')->store('bahan-pangan', 'public');
    $secondPath = UploadedFile::fake()->image('second.jpg')->store('bahan-pangan', 'public');
    $first = BahanPangan::factory()->create(['image' => $firstPath]);
    $second = BahanPangan::factory()->create(['image' => $secondPath]);
    $untouched = BahanPangan::factory()->create();

    Livewire::test('pages::bahan-pangan.index')
        ->set('selectedIds', [$first->id, $second->id])
        ->call('bulkDelete')
        ->assertSet('selectedIds', []);

    expect(BahanPangan::query()->whereKey([$first->id, $second->id])->exists())->toBeFalse()
        ->and($untouched->fresh())->not->toBeNull();
    Storage::disk('public')->assertMissing($firstPath);
    Storage::disk('public')->assertMissing($secondPath);
});
