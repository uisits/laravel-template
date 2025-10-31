<?php

use App\Models\User;
use Database\Factories\UserFactory;

test('user factory exists', function () {
    expect(class_exists(UserFactory::class))->toBeTrue();
});

test('user factory can create user', function () {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class)
        ->id->not->toBeNull()
        ->name->not->toBeNull()
        ->email->not->toBeNull();
});

test('user factory can create multiple users', function () {
    $users = User::factory()->count(5)->create();

    expect($users)->toHaveCount(5);
    expect($users->first())->toBeInstanceOf(User::class);
});

test('user factory can make user without persisting', function () {
    $user = User::factory()->make();

    expect($user)->toBeInstanceOf(User::class)
        ->id->toBeNull(); // Not persisted yet
});

test('user factory can override attributes', function () {
    $user = User::factory()->create([
        'name' => 'Custom Name',
        'email' => 'custom@example.com',
    ]);

    expect($user->name)->toBe('Custom Name')
        ->and($user->email)->toBe('custom@example.com');
});

test('user factory creates unique emails', function () {
    $users = User::factory()->count(10)->create();

    $emails = $users->pluck('email')->toArray();
    $uniqueEmails = array_unique($emails);

    expect(count($emails))->toBe(count($uniqueEmails));
});

test('user factory can use state modifiers', function () {
    $verifiedUser = User::factory()->create();
    $unverifiedUser = User::factory()->unverified()->create();

    expect($verifiedUser->email_verified_at)->not->toBeNull()
        ->and($unverifiedUser->email_verified_at)->toBeNull();
});

test('user factory sets password correctly', function () {
    $user = User::factory()->create();

    expect($user->password)->not->toBeNull()
        ->and($user->password)->not->toBe('password') // Should be hashed
        ->and(strlen($user->password))->toBeGreaterThan(20); // Hashed password is long
});

test('user factory can create user with relationships', function () {
    $user = User::factory()
        ->create();

    // Can assign roles after creation
    $role = \Spatie\Permission\Models\Role::create(['name' => 'test-role']);
    $user->assignRole($role);

    expect($user->roles)->toHaveCount(1);
});

test('multiple users can be created in sequence', function () {
    $user1 = User::factory()->create(['email' => 'user1@example.com']);
    $user2 = User::factory()->create(['email' => 'user2@example.com']);
    $user3 = User::factory()->create(['email' => 'user3@example.com']);

    expect(User::count())->toBeGreaterThanOrEqual(3);
});
