<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Create a user with a specific role
 */
function createUserWithRole(string $roleName): User
{
    $user = User::factory()->create();
    $role = Role::firstOrCreate(['name' => $roleName]);
    $user->assignRole($role);

    return $user;
}

/**
 * Create a user with specific permissions
 */
function createUserWithPermissions(array $permissions): User
{
    $user = User::factory()->create();

    foreach ($permissions as $permission) {
        $perm = Permission::firstOrCreate(['name' => $permission]);
        $user->givePermissionTo($perm);
    }

    return $user;
}

/**
 * Act as an authenticated user
 */
function actingAsUser(?User $user = null): User
{
    $user = $user ?? User::factory()->create();
    test()->actingAs($user);

    return $user;
}

function superAdmin(): User
{
    $user = User::factory()->create();
    $user->assignRole(['panel_user', 'super_admin']);

    return $user;
}

function admin(): User
{
    $user = User::factory()->create();
    $user->assignRole(['panel_user', 'admin']);

    return $user;
}
function panelUser(): User
{
    $user = User::factory()->create();
    $user->assignRole(['panel_user']);

    return $user;
}
function asSuperAdmin(): TestCase
{
    return test()->actingAs(superAdmin());
}

function asAdmin(): TestCase
{
    return test()->actingAs(admin());
}

function asPanelUser(): TestCase
{
    return test()->actingAs(panelUser());
}
