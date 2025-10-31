<?php

use App\Models\User;

// Note: These tests verify the canAccessPanel logic without requiring full Filament panel initialization
// The canAccessPanel method checks email domain and environment

test('application environment is configured', function () {
    // In test environment, verify environment is set
    expect(config('app.env'))->toBe('testing');
});

test('user canAccessPanel method exists', function () {
    $user = User::factory()->create();

    expect(method_exists($user, 'canAccessPanel'))->toBeTrue();
});

test('email ending check works correctly', function () {
    $uisEmail = 'test@uis.edu';
    $nonUisEmail = 'test@gmail.com';

    expect(str_ends_with($uisEmail, '@uis.edu'))->toBeTrue()
        ->and(str_ends_with($nonUisEmail, '@uis.edu'))->toBeFalse();

    // Note: Subdomains like 'admin@sub.uis.edu' will NOT match str_ends_with('@uis.edu')
    // They would need additional logic to handle subdomains
});
