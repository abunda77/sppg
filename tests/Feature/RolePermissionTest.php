<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('seeder creates all roles from Role.md', function () {
    $expectedRoles = [
        'super_admin', 'admin_unit', 'ahli_gizi', 'purchasing',
        'gudang', 'keuangan', 'hr', 'koordinator_distribusi',
        'sopir', 'manajemen',
    ];

    foreach ($expectedRoles as $roleName) {
        expect(Role::where('name', $roleName)->exists())->toBeTrue();
    }
});

test('seeder creates permissions for each role', function () {
    expect(Permission::count())->toBeGreaterThan(0);

    $adminUnit = Role::findByName('admin_unit');
    expect($adminUnit->hasPermissionTo('unit.view'))->toBeTrue();
    expect($adminUnit->hasPermissionTo('dashboard.view'))->toBeTrue();

    $ahliGizi = Role::findByName('ahli_gizi');
    expect($ahliGizi->hasPermissionTo('bahan-pangan.view'))->toBeTrue();
    expect($ahliGizi->hasPermissionTo('menu.create'))->toBeTrue();
});

test('super_admin role has no direct permissions', function () {
    $superAdmin = Role::findByName('super_admin');
    expect($superAdmin->permissions)->toHaveCount(0);
});

test('seeder is non-destructive and can run multiple times', function () {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(RolePermissionSeeder::class);

    expect(Role::where('name', 'super_admin')->count())->toBe(1);
    expect(Role::where('name', 'admin_unit')->count())->toBe(1);
});

test('user can be assigned a role', function () {
    $user = User::factory()->create();
    $user->assignRole('ahli_gizi');

    expect($user->hasRole('ahli_gizi'))->toBeTrue();
    expect($user->hasPermissionTo('bahan-pangan.view'))->toBeTrue();
});

test('guests cannot access admin users page', function () {
    $this->get(route('admin.users'))->assertRedirect(route('login'));
});

test('non-super_admin users cannot access admin pages', function () {
    $user = User::factory()->create();
    $user->assignRole('admin_unit');

    $this->actingAs($user)
        ->get(route('admin.users'))
        ->assertForbidden();
});

test('super_admin can access admin users page', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get(route('admin.users'))
        ->assertSuccessful();
});

test('super_admin can access admin roles page', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get(route('admin.roles'))
        ->assertSuccessful();
});

test('super_admin can access admin permissions page', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $this->actingAs($user)
        ->get(route('admin.permissions'))
        ->assertSuccessful();
});

test('super_admin can update user password via edit modal', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super_admin');

    $targetUser = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->actingAs($superAdmin);

    Livewire::test('pages::admin.users')
        ->call('editUser', $targetUser->id)
        ->assertSet('editName', $targetUser->name)
        ->assertSet('editEmail', $targetUser->email)
        ->assertSet('editPassword', '')
        ->set('editPassword', 'new-secure-password')
        ->call('updateUser')
        ->assertHasNoErrors();

    expect(Hash::check('new-secure-password', $targetUser->fresh()->password))->toBeTrue();
});

test('editing user without filling password does not change current password', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super_admin');

    $targetUser = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->actingAs($superAdmin);

    Livewire::test('pages::admin.users')
        ->call('editUser', $targetUser->id)
        ->set('editPassword', '')
        ->call('updateUser')
        ->assertHasNoErrors();

    expect(Hash::check('old-password', $targetUser->fresh()->password))->toBeTrue();
});

test('password validation rejects password shorter than 8 characters', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super_admin');

    $targetUser = User::factory()->create();

    $this->actingAs($superAdmin);

    Livewire::test('pages::admin.users')
        ->call('editUser', $targetUser->id)
        ->set('editPassword', 'short')
        ->call('updateUser')
        ->assertHasErrors(['editPassword' => 'min']);
});
