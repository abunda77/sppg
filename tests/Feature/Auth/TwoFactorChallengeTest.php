<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
});

test('two factor challenge redirects to login when not authenticated', function () {
    $response = $this->get(route('two-factor.login'));

    $response->assertRedirect(route('login'));
});

test('two factor challenge can be rendered', function () {
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('two-factor.login'));

    $response = $this->get(route('two-factor.login'));

    $response
        ->assertOk()
        ->assertSee('Authentication code')
        ->assertSee('images/welcome/logo_PMJ.png')
        ->assertSee('images/welcome/logo_LOGISTIK_PMJ.png')
        ->assertSee('images/welcome/logo.png')
        ->assertSee('rounded-[2rem]', escape: false);
});
