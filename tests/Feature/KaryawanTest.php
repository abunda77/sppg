<?php

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Database\Seeders\DivisiSeeder;
use Database\Seeders\JabatanSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->seed(JabatanSeeder::class);
    $this->seed(DivisiSeeder::class);
});

test('guests are redirected to the login page', function () {
    auth()->logout();

    $response = $this->get(route('karyawan.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit karyawan page', function () {
    $response = $this->get(route('karyawan.index'));
    $response->assertOk();
});

test('karyawan page displays data', function () {
    $jabatan = Jabatan::first();
    $divisi = Divisi::first();

    Karyawan::factory()->create([
        'nama' => 'Budi Santoso',
        'jabatan_id' => $jabatan->id,
        'divisi_id' => $divisi->id,
    ]);

    Livewire::test('pages::karyawan.index')
        ->assertSee('Budi Santoso');
});

test('can search karyawan', function () {
    Karyawan::factory()->create(['nama' => 'Budi Santoso']);
    Karyawan::factory()->create(['nama' => 'Ani Wijaya']);

    Livewire::test('pages::karyawan.index')
        ->set('search', 'Budi')
        ->assertSee('Budi Santoso')
        ->assertDontSee('Ani Wijaya');
});

test('can search karyawan by jabatan', function () {
    $jabatan = Jabatan::first();

    Karyawan::factory()->create([
        'nama' => 'Budi Santoso',
        'jabatan_id' => $jabatan->id,
    ]);
    Karyawan::factory()->create(['nama' => 'Ani Wijaya']);

    Livewire::test('pages::karyawan.index')
        ->set('search', $jabatan->nama)
        ->assertSee('Budi Santoso');
});

test('can create karyawan', function () {
    $jabatan = Jabatan::first();
    $divisi = Divisi::first();

    Livewire::test('pages::karyawan.index')
        ->call('openCreateModal')
        ->assertSet('showCreateModal', true)
        ->set('createNama', 'Budi Santoso')
        ->set('createEmail', 'budi@example.com')
        ->set('createJabatanId', $jabatan->id)
        ->set('createDivisiId', $divisi->id)
        ->set('createNoTelp', '08123456789')
        ->call('createKaryawan');

    expect(Karyawan::where('email', 'budi@example.com')->exists())->toBeTrue();
});

test('create karyawan requires nama', function () {
    $jabatan = Jabatan::first();
    $divisi = Divisi::first();

    Livewire::test('pages::karyawan.index')
        ->call('openCreateModal')
        ->set('createNama', '')
        ->set('createEmail', 'budi@example.com')
        ->set('createJabatanId', $jabatan->id)
        ->set('createDivisiId', $divisi->id)
        ->set('createNoTelp', '08123456789')
        ->call('createKaryawan')
        ->assertHasErrors(['createNama']);

    expect(Karyawan::count())->toBe(0);
});

test('create karyawan requires valid jabatan', function () {
    $divisi = Divisi::first();

    Livewire::test('pages::karyawan.index')
        ->call('openCreateModal')
        ->set('createNama', 'Budi')
        ->set('createEmail', 'budi@example.com')
        ->set('createJabatanId', 9999)
        ->set('createDivisiId', $divisi->id)
        ->set('createNoTelp', '08123456789')
        ->call('createKaryawan')
        ->assertHasErrors(['createJabatanId']);

    expect(Karyawan::count())->toBe(0);
});

test('can edit karyawan', function () {
    $karyawan = Karyawan::factory()->create(['nama' => 'Nama Lama']);
    $newJabatan = Jabatan::orderBy('id', 'desc')->first();

    Livewire::test('pages::karyawan.index')
        ->call('editKaryawan', $karyawan->id)
        ->assertSet('editNama', 'Nama Lama')
        ->set('editNama', 'Nama Baru')
        ->set('editJabatanId', $newJabatan->id)
        ->call('updateKaryawan');

    expect(Karyawan::find($karyawan->id)->nama)->toBe('Nama Baru')
        ->and(Karyawan::find($karyawan->id)->jabatan_id)->toBe($newJabatan->id);
});

test('can delete karyawan', function () {
    $karyawan = Karyawan::factory()->create();

    Livewire::test('pages::karyawan.index')
        ->call('deleteKaryawan', $karyawan->id);

    expect(Karyawan::find($karyawan->id))->toBeNull();
});

test('can bulk delete karyawan', function () {
    $k1 = Karyawan::factory()->create();
    $k2 = Karyawan::factory()->create();

    Livewire::test('pages::karyawan.index')
        ->set('selectedIds', [$k1->id, $k2->id])
        ->call('bulkDelete');

    expect(Karyawan::count())->toBe(0);
});

test('import csv creates and updates karyawan', function () {
    $jabatan = Jabatan::first();
    $divisi = Divisi::first();

    Karyawan::factory()->create([
        'nama' => 'Existing User',
        'email' => 'existing@example.com',
    ]);

    $csv = implode("\n", [
        'nama,email,jabatan,divisi,no_telp',
        "Budi Santoso,budi@example.com,{$jabatan->nama},{$divisi->nama},08123456789",
        "Existing User,existing@example.com,{$jabatan->nama},{$divisi->nama},08987654321",
    ]);

    Livewire::test('pages::karyawan.index')
        ->set('importFile', UploadedFile::fake()->createWithContent('karyawan.csv', $csv))
        ->call('importCsv')
        ->assertHasNoErrors()
        ->assertSet('showImportModal', false);

    expect(Karyawan::where('email', 'budi@example.com')->exists())->toBeTrue()
        ->and(Karyawan::where('email', 'existing@example.com')->count())->toBe(1)
        ->and(Karyawan::where('email', 'existing@example.com')->first()->no_telp)->toBe('08987654321');
});

test('invalid csv import does not change any data', function () {
    $item = Karyawan::factory()->create([
        'nama' => 'Existing User',
        'email' => 'existing@example.com',
    ]);

    $csv = implode("\n", [
        'nama,email,jabatan,divisi,no_telp',
        ',invalid-email,Jabatan Tidak Ada,Divisi Tidak Ada,',
    ]);

    Livewire::test('pages::karyawan.index')
        ->set('importFile', UploadedFile::fake()->createWithContent('karyawan.csv', $csv))
        ->call('importCsv')
        ->assertHasErrors(['importFile']);

    expect($item->fresh()->nama)->toBe('Existing User')
        ->and(Karyawan::count())->toBe(1);
});

test('can export filtered karyawan to csv', function () {
    $jabatan = Jabatan::first();
    $divisi = Divisi::first();

    Karyawan::factory()->create([
        'nama' => 'Budi',
        'email' => 'budi@example.com',
        'jabatan_id' => $jabatan->id,
        'divisi_id' => $divisi->id,
        'no_telp' => '08123456789',
    ]);
    Karyawan::factory()->create(['nama' => 'Ani']);

    Livewire::test('pages::karyawan.index')
        ->set('search', 'Budi')
        ->call('exportCsv')
        ->assertFileDownloaded('karyawan.csv', implode("\n", [
            'nama,email,jabatan,divisi,no_telp',
            "Budi,budi@example.com,\"{$jabatan->nama}\",{$divisi->nama},08123456789",
            '',
        ]));
});

test('can export karyawan to pdf', function () {
    Karyawan::factory()->create(['nama' => 'Test User']);

    Livewire::test('pages::karyawan.index')
        ->call('exportPdf')
        ->assertFileDownloaded();
});
