# Test Suite

This test suite is built using [PestPHP](https://pestphp.com/), a delightful PHP testing framework with a focus on simplicity.

# Setup
> [!CAUTION]
> Please make sure that the entry `<env name="DB_CONNECTION" value="sqlite"/>` is set to `sqlite` in the `phpunit.xml` file.
> Setting it to any other value will wipe the database and loose all data.

## Running Tests

Run all tests:
```bash
php artisan test
```

Or using Pest directly:
```bash
./vendor/bin/pest
```

Run specific test suites:
```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

Run tests with coverage:
```bash
php artisan test --coverage
```

Run a specific test file:
```bash
php artisan test tests/Unit/UserTest.php
```

Run tests in a folder:
```bash
php artisan test tests/Unit/
php artisan test tests/Feature/Auth/
```

Run tests in parallel (faster):
```bash
php artisan test --parallel
```

## Test Structure

### Unit Tests (`tests/Unit/`)

> [!NOTE]
> Unit testing is a method of software testing where individual, isolated components or "units" of code, typically functions or methods, are tested to ensure they work as expected according to the developer's logic.

- **UserTest.php** - Tests for User model functionality, attributes, and relationships
- **UserPolicyTest.php** - Tests for user authorization policies
- **FactoriesTest.php** - Tests for database factories
- **Helpers/StudentClassTest.php** - Tests for helper classes (placeholder tests included)

### Feature Tests (`tests/Feature/`)

> [!NOTE]
> Feature testing is a software development process that evaluates individual features of an application to ensure they function as intended, meet specifications, and deliver an improved user experience.

- **Auth/AuthenticationTest.php** - Tests for authentication flows
- **Auth/FilamentPanelAccessTest.php** - Tests for Filament panel access control
- **Auth/RolesAndPermissionsTest.php** - Tests for role and permission management
- **Filament/UserResourceTest.php** - Tests for Filament UserResource CRUD operations
- **ApplicationTest.php** - General application tests
- **DatabaseTest.php** - Database schema and connection tests
- **MiddlewareTest.php** - Middleware functionality tests

## Test Helpers

The following helper functions are available globally in tests (defined in `tests/Pest.php`):

- **`createUserWithRole(string $roleName)`** - Create a user with a specific role
- **`createUserWithPermissions(array $permissions)`** - Create a user with specific permissions
- **`actingAsUser(?User $user = null)`** - Act as an authenticated user

Example usage:
```php
test('admin can access dashboard', function () {
    $admin = createUserWithRole('admin');

    $response = $this->actingAs($admin)->get('/app');

    $response->assertOk();
});
```

## Configuration

Tests are configured to use:
- SQLite in-memory database (`:memory:`)
- Array cache driver
- Sync queue driver
- Array mail driver
- Disabled Telescope

This configuration is defined in `phpunit.xml` and ensures tests run quickly without affecting your development database.

## Writing New Tests

### Unit Test Example
```php
<?php

use App\Models\User;

test('user can be created', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    expect($user->email)->toBe('test@example.com');
});
```

### Feature Test Example
```php
<?php

test('user can view dashboard when authenticated', function () {
    $user = actingAsUser();

    $response = $this->get('/app');

    $response->assertOk();
});
```

### Filament Resource Test Example
```php
<?php

use App\Filament\Resources\Users\Pages\ListUsers;
use Livewire\Livewire;

test('users can be listed', function () {
    $user = createUserWithPermissions(['ViewAny:User']);

    Livewire::actingAs($user)
        ->test(ListUsers::class)
        ->assertSuccessful();
});
```

## Test Database

The test suite uses an in-memory SQLite database that is refreshed before each test. The `RefreshDatabase` trait is automatically applied to all tests in the Feature and Unit directories.

## Continuous Integration

To run tests in CI/CD:

```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
php artisan key:generate
php artisan test --parallel
```

## Notes

- All tests use the `RefreshDatabase` trait to ensure a clean database state
- Factories should be used to create test data
- Tests should be independent and not rely on execution order
- Use descriptive test names that explain what is being tested
- Helper tests for StudentClass are placeholders - update them when required models are available

## Troubleshooting

If tests fail:

1. Ensure all dependencies are installed: `composer install`
2. Clear cache: `php artisan config:clear && php artisan cache:clear`
3. Check database migrations: `php artisan migrate:fresh`
4. Verify `.env.testing` file exists (if used)
5. Check test output for specific error messages

## Resources

- [PestPHP Documentation](https://pestphp.com/docs)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Filament Testing Documentation](https://filamentphp.com/docs/panels/testing)
