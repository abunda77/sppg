<?php

use App\Http\Controllers\PublicBahanPanganController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('bahan-pangan', 'pages::bahan-pangan.index')->name('bahan-pangan.index');

    Route::middleware(['role:super_admin'])->group(function () {
        Route::livewire('admin/users', 'pages::admin.users')->name('admin.users');
        Route::livewire('admin/roles', 'pages::admin.roles')->name('admin.roles');
        Route::livewire('admin/permissions', 'pages::admin.permissions')->name('admin.permissions');
    });
});

require __DIR__.'/settings.php';

Route::get('katalog-bahan-pangan', [PublicBahanPanganController::class, 'index'])->name('katalog.bahan-pangan');
