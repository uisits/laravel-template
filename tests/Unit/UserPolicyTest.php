<?php

use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    $this->policy = new UserPolicy();
});

test('user can view any users with proper permission', function () {
    $user = createUserWithPermissions(['ViewAny:User']);

    expect($this->policy->viewAny($user))->toBeTrue();
});

test('user cannot view any users without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->viewAny($user))->toBeFalse();
});

test('user can view user with proper permission', function () {
    $user = createUserWithPermissions(['View:User']);

    expect($this->policy->view($user))->toBeTrue();
});

test('user cannot view user without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->view($user))->toBeFalse();
});

test('user can create user with proper permission', function () {
    $user = createUserWithPermissions(['Create:User']);

    expect($this->policy->create($user))->toBeTrue();
});

test('user cannot create user without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->create($user))->toBeFalse();
});

test('user can update user with proper permission', function () {
    $user = createUserWithPermissions(['Update:User']);

    expect($this->policy->update($user))->toBeTrue();
});

test('user cannot update user without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->update($user))->toBeFalse();
});

test('user can delete user with proper permission', function () {
    $user = createUserWithPermissions(['Delete:User']);

    expect($this->policy->delete($user))->toBeTrue();
});

test('user cannot delete user without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->delete($user))->toBeFalse();
});

test('user can restore user with proper permission', function () {
    $user = createUserWithPermissions(['Restore:User']);

    expect($this->policy->restore($user))->toBeTrue();
});

test('user cannot restore user without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->restore($user))->toBeFalse();
});

test('user can force delete user with proper permission', function () {
    $user = createUserWithPermissions(['ForceDelete:User']);

    expect($this->policy->forceDelete($user))->toBeTrue();
});

test('user cannot force delete user without proper permission', function () {
    $user = User::factory()->create();

    expect($this->policy->forceDelete($user))->toBeFalse();
});

test('user can force delete any user with proper permission', function () {
    $user = createUserWithPermissions(['ForceDeleteAny:User']);

    expect($this->policy->forceDeleteAny($user))->toBeTrue();
});

test('user can restore any user with proper permission', function () {
    $user = createUserWithPermissions(['RestoreAny:User']);

    expect($this->policy->restoreAny($user))->toBeTrue();
});

test('user can replicate user with proper permission', function () {
    $user = createUserWithPermissions(['Replicate:User']);

    expect($this->policy->replicate($user))->toBeTrue();
});

test('user can reorder users with proper permission', function () {
    $user = createUserWithPermissions(['Reorder:User']);

    expect($this->policy->reorder($user))->toBeTrue();
});

test('policy methods check correct permissions', function () {
    $permissions = [
        'ViewAny:User',
        'View:User',
        'Create:User',
        'Update:User',
        'Delete:User',
        'Restore:User',
        'ForceDelete:User',
        'ForceDeleteAny:User',
        'RestoreAny:User',
        'Replicate:User',
        'Reorder:User',
    ];

    foreach ($permissions as $permission) {
        Permission::updateOrCreate(['name' => $permission]);
    }

    $user = User::factory()->create();
    foreach ($permissions as $permission) {
        $user->givePermissionTo($permission);
    }

    expect($this->policy->viewAny($user))->toBeTrue()
        ->and($this->policy->view($user))->toBeTrue()
        ->and($this->policy->create($user))->toBeTrue()
        ->and($this->policy->update($user))->toBeTrue()
        ->and($this->policy->delete($user))->toBeTrue()
        ->and($this->policy->restore($user))->toBeTrue()
        ->and($this->policy->forceDelete($user))->toBeTrue()
        ->and($this->policy->forceDeleteAny($user))->toBeTrue()
        ->and($this->policy->restoreAny($user))->toBeTrue()
        ->and($this->policy->replicate($user))->toBeTrue()
        ->and($this->policy->reorder($user))->toBeTrue();
});
