<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('database connection is working', function () {
    expect(DB::connection()->getDatabaseName())->not->toBeNull();
});

test('users table exists', function () {
    expect(Schema::hasTable('users'))->toBeTrue();
});

test('users table has expected columns', function () {
    $expectedColumns = [
        'id',
        'name',
        'email',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    foreach ($expectedColumns as $column) {
        expect(Schema::hasColumn('users', $column))
            ->toBeTrue("Users table should have {$column} column");
    }
});

test('roles table exists', function () {
    expect(Schema::hasTable('roles'))->toBeTrue();
});

test('permissions table exists', function () {
    expect(Schema::hasTable('permissions'))->toBeTrue();
});

test('model_has_permissions table exists', function () {
    expect(Schema::hasTable('model_has_permissions'))->toBeTrue();
});

test('model_has_roles table exists', function () {
    expect(Schema::hasTable('model_has_roles'))->toBeTrue();
});

test('role_has_permissions table exists', function () {
    expect(Schema::hasTable('role_has_permissions'))->toBeTrue();
});

test('cache table exists', function () {
    expect(Schema::hasTable('cache'))->toBeTrue();
});

test('jobs table exists', function () {
    expect(Schema::hasTable('jobs'))->toBeTrue();
});

test('failed_jobs table exists', function () {
    expect(Schema::hasTable('failed_jobs'))->toBeTrue();
});

test('database can create user', function () {
    $user = User::factory()->create([
        'email' => 'database-test@example.com',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'database-test@example.com',
    ]);
});

test('database can update user', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
    ]);

    $user->update(['name' => 'New Name']);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
    ]);
});

test('database can delete user', function () {
    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();

    $this->assertDatabaseMissing('users', [
        'id' => $userId,
    ]);
});

test('database transactions work correctly', function () {
    DB::beginTransaction();

    $user = User::factory()->create([
        'email' => 'transaction-test@example.com',
    ]);

    DB::rollBack();

    $this->assertDatabaseMissing('users', [
        'email' => 'transaction-test@example.com',
    ]);
});

test('database seeder exists', function () {
    // Verify the seeder class exists
    expect(class_exists('Database\\Seeders\\DatabaseSeeder'))->toBeTrue();
});
