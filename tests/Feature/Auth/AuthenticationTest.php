<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

// Note: This application uses Filament for authentication which has its own routes
// These tests verify core authentication concepts work correctly

test('user password is hashed correctly', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    expect(Hash::check('password', $user->password))->toBeTrue()
        ->and(Hash::check('wrong-password', $user->password))->toBeFalse();
});

test('user can be authenticated programmatically', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->assertAuthenticated();
    expect(auth()->user()->id)->toBe($user->id);
});

test('guest is not authenticated', function () {
    $this->assertGuest();
    expect(auth()->guest())->toBeTrue();
});

test('user has sanctum token functionality', function () {
    $user = User::factory()->create();

    // Verify the HasApiTokens trait is being used
    expect(method_exists($user, 'createToken'))->toBeTrue()
        ->and(method_exists($user, 'tokens'))->toBeTrue();
});

test('sanctum is configured', function () {
    // Verify Sanctum is installed
    expect(class_exists(Sanctum::class))->toBeTrue();
});
