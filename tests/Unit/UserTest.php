<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('user can be created with factory', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($user)
        ->toBeInstanceOf(User::class)
        ->name->toBe('Test User')
        ->email->toBe('test@example.com');
});

test('user password is hidden from array', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret'),
    ]);

    $array = $user->toArray();

    expect($array)->not->toHaveKey('password');
});

test('user has hidden attributes', function () {
    $user = User::factory()->make();

    expect($user->getHidden())->toContain(
        'password',
        'remember_token',
        'access_token',
        'id_token',
        'refresh_token',
        'token'
    );
});

test('user email verified at is cast to datetime', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('user can be assigned roles', function () {
    $user = User::factory()->create();
    $role = Role::updateOrCreate(['name' => 'admin']);

    $user->assignRole($role);

    expect($user->hasRole('admin'))->toBeTrue();
});

test('user can be assigned permissions', function () {
    $user = User::factory()->create();
    $permission = Permission::create(['name' => 'edit posts']);

    $user->givePermissionTo($permission);

    expect($user->hasPermissionTo('edit posts'))->toBeTrue();
});

test('user can check permission via role', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'editor']);
    $permission = Permission::create(['name' => 'edit posts']);

    $role->givePermissionTo($permission);
    $user->assignRole($role);

    expect($user->hasPermissionTo('edit posts'))->toBeTrue();
});

test('user factory creates valid user', function () {
    $user = User::factory()->create();

    expect($user)
        ->name->not->toBeEmpty()
        ->email->not->toBeEmpty()
        ->email_verified_at->not->toBeNull()
        ->password->not->toBeEmpty();
});

test('user factory can create unverified user', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

test('user uses guarded property correctly', function () {
    $user = new User();

    expect($user->getGuarded())->toBe([]);
});
