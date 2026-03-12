<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

test('role can be created', function () {
    $role = Role::updateOrCreate(['name' => 'admin']);

    expect($role)->toBeInstanceOf(Role::class)
        ->name->toBe('admin');
});

test('permission can be created', function () {
    $permission = Permission::updateOrCreate(['name' => 'edit posts']);

    expect($permission)->toBeInstanceOf(Permission::class)
        ->name->toBe('edit posts');
});

test('user can be assigned multiple roles', function () {
    $user = User::factory()->create();
    $adminRole = Role::updateOrCreate(['name' => 'admin']);
    $editorRole = Role::updateOrCreate(['name' => 'editor']);

    $user->assignRole([$adminRole, $editorRole]);

    expect($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasRole('editor'))->toBeTrue()
        ->and($user->roles)->toHaveCount(2);
});

test('user can be assigned multiple permissions', function () {
    $user = User::factory()->create();
    $editPermission = Permission::updateOrCreate(['name' => 'edit posts']);
    $deletePermission = Permission::updateOrCreate(['name' => 'delete posts']);

    $user->givePermissionTo([$editPermission, $deletePermission]);

    expect($user->hasPermissionTo('edit posts'))->toBeTrue()
        ->and($user->hasPermissionTo('delete posts'))->toBeTrue()
        ->and($user->permissions)->toHaveCount(2);
});

test('role can have multiple permissions', function () {
    $role = Role::updateOrCreate(['name' => 'editor']);
    $editPermission = Permission::updateOrCreate(['name' => 'edit posts']);
    $deletePermission = Permission::updateOrCreate(['name' => 'delete posts']);

    $role->givePermissionTo([$editPermission, $deletePermission]);

    expect($role->hasPermissionTo('edit posts'))->toBeTrue()
        ->and($role->hasPermissionTo('delete posts'))->toBeTrue()
        ->and($role->permissions)->toHaveCount(2);
});

test('user inherits permissions from role', function () {
    $user = User::factory()->create();
    $role = Role::updateOrCreate(['name' => 'editor']);
    $permission = Permission::updateOrCreate(['name' => 'edit posts']);

    $role->givePermissionTo($permission);
    $user->assignRole($role);

    expect($user->hasPermissionTo('edit posts'))->toBeTrue();
});

test('user can have direct permissions and role permissions', function () {
    $user = User::factory()->create();
    $role = Role::updateOrCreate(['name' => 'editor']);

    $editPermission = Permission::updateOrCreate(['name' => 'edit posts']);
    $deletePermission = Permission::updateOrCreate(['name' => 'delete posts']);

    $role->givePermissionTo($editPermission);
    $user->assignRole($role);
    $user->givePermissionTo($deletePermission);

    expect($user->hasPermissionTo('edit posts'))->toBeTrue()
        ->and($user->hasPermissionTo('delete posts'))->toBeTrue();
});

test('user can be removed from role', function () {
    $user = User::factory()->create();
    $role = Role::updateOrCreate(['name' => 'admin']);

    $user->assignRole($role);
    expect($user->hasRole('admin'))->toBeTrue();

    $user->removeRole($role);
    expect($user->hasRole('admin'))->toBeFalse();
});

test('user permission can be revoked', function () {
    $user = User::factory()->create();
    $permission = Permission::updateOrCreate(['name' => 'edit posts']);

    $user->givePermissionTo($permission);
    expect($user->hasPermissionTo('edit posts'))->toBeTrue();

    $user->revokePermissionTo($permission);
    expect($user->hasPermissionTo('edit posts'))->toBeFalse();
});

test('user can check any permission', function () {
    $user = User::factory()->create();
    $permission1 = Permission::updateOrCreate(['name' => 'edit posts']);
    $permission2 = Permission::updateOrCreate(['name' => 'delete posts']);

    $user->givePermissionTo($permission1);

    expect($user->hasAnyPermission(['edit posts', 'delete posts']))->toBeTrue()
        ->and($user->hasAnyPermission(['delete posts', 'create posts']))->toBeFalse();
});

test('user can check all permissions', function () {
    $user = User::factory()->create();
    $permission1 = Permission::updateOrCreate(['name' => 'edit posts']);
    $permission2 = Permission::updateOrCreate(['name' => 'delete posts']);

    $user->givePermissionTo([$permission1, $permission2]);

    expect($user->hasAllPermissions(['edit posts', 'delete posts']))->toBeTrue()
        ->and($user->hasAllPermissions(['edit posts', 'create posts']))->toBeFalse();
});

test('user can sync roles', function () {
    $user = User::factory()->create();
    $adminRole = Role::updateOrCreate(['name' => 'admin']);
    $editorRole = Role::updateOrCreate(['name' => 'editor']);

    $user->assignRole($adminRole);
    $user->syncRoles([$editorRole]);

    expect($user->hasRole('admin'))->toBeFalse()
        ->and($user->hasRole('editor'))->toBeTrue()
        ->and($user->roles)->toHaveCount(1);
});

test('user can sync permissions', function () {
    $user = User::factory()->create();
    $editPermission = Permission::updateOrCreate(['name' => 'edit posts']);
    $deletePermission = Permission::updateOrCreate(['name' => 'delete posts']);

    $user->givePermissionTo($editPermission);
    $user->syncPermissions([$deletePermission]);

    expect($user->hasPermissionTo('edit posts'))->toBeFalse()
        ->and($user->hasPermissionTo('delete posts'))->toBeTrue()
        ->and($user->permissions)->toHaveCount(1);
});

test('helper function creates user with role correctly', function () {
    $user = createUserWithRole('admin');

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->hasRole('admin'))->toBeTrue();
});

test('helper function creates user with permissions correctly', function () {
    $user = createUserWithPermissions(['edit posts', 'delete posts']);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->hasPermissionTo('edit posts'))->toBeTrue()
        ->and($user->hasPermissionTo('delete posts'))->toBeTrue();
});
