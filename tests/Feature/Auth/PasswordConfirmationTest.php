<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response
        ->assertOk()
        ->assertSee('Confirm password')
        ->assertSee('images/welcome/logo_PMJ.png')
        ->assertSee('images/welcome/logo_LOGISTIK_PMJ.png')
        ->assertSee('images/welcome/logo.png')
        ->assertSee('rounded-[2rem]', escape: false);
});
