<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = Mockery::mock(User::factory()->make())->makePartial();
    $user->shouldReceive('hasRole')->andReturnFalse();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk()
        ->assertSeeTextInOrder([
            'Dashboard Operasional',
            'Ringkasan distribusi, stok, dan kesiapan layanan hari ini.',
            'Target Porsi Hari Ini',
            'Menu Hari Ini',
            'Tahapan Produksi',
            'Ringkasan Keuangan',
        ])
        ->assertSee('data-dashboard-shell="operational"', false);
});
