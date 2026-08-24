# Testing (Pest 4) — UIS ITS

Every change ships with a test. Run the narrowest set that proves it:

```bash
php artisan test --compact --filter='thing can be created'
php artisan test --compact tests/Feature/Filament/ThingResourceTest.php
php artisan test --compact              # whole suite before finishing
```

## Setup you must know

- `tests/Pest.php` binds `Tests\TestCase` **and** `RefreshDatabase` to everything in `Feature` and
  `Unit`. Don't re-declare `uses(RefreshDatabase::class)`.
- `Tests\TestCase::setUp()` seeds `RoleSeeder` before every test, which runs `shield:install app -n`
  and `shield:generate --all --panel=app -n`. So **all Shield permissions and the three roles
  already exist inside every test** — assign roles, don't create permissions by hand.
- `phpunit.xml` pins `DB_CONNECTION=sqlite` with `DB_DATABASE=./database/test.sqlite` (a file, not
  `:memory:`), array cache/mail/session, sync queue, Telescope and Pulse disabled.

> **Danger:** `tests/README.md` warns about this and it is real — if `DB_CONNECTION` in `phpunit.xml`
> is ever pointed at a live connection, `RefreshDatabase` will wipe it. Never change that value, and
> never run the suite with a `.env` that overrides it toward MySQL or Oracle.

## Helpers available globally

Defined in `tests/Pest.php` — use them instead of building users inline:

```php
superAdmin(); admin(); panelUser();              // User with roles assigned
asSuperAdmin(); asAdmin(); asPanelUser();        // returns TestCase, already actingAs
createUserWithRole('admin');
createUserWithPermissions(['ViewAny:User']);
actingAsUser($user = null);
```

Typical Filament test:

```php
use function Pest\Livewire\livewire;

it('lists things for an admin', function () {
    asAdmin();
    $things = Thing::factory()->count(3)->create();

    livewire(ListThings::class)
        ->assertCanSeeTableRecords($things)
        ->searchTable($things->first()->name)
        ->assertCanSeeTableRecords($things->take(1));
});

it('forbids a plain panel user', function () {
    asPanelUser();

    livewire(ListThings::class)->assertForbidden();
});
```

Always pair a "can" test with a "cannot" test — authorization is the most common source of bugs in
these apps.

## What to test for a new feature

1. **Policy/permission**: each role that should and should not reach it.
2. **Happy path** through the Filament page (`fillForm` → `call('create')` → `assertHasNoFormErrors`
   → `assertDatabaseHas`). For edit pages pass `['record' => $id]`, call `save`, and don't assert a
   redirect.
3. **Validation failures** with `assertHasFormErrors([...])`.
4. **Business rules** as unit tests on the helper/model, not through the UI.

## External systems in tests

LDAP, the OIDC providers, Oracle, and campus HTTP APIs are **not reachable from the test suite**.

- HTTP: `Http::fake()` / `Http::preventStrayRequests()`.
- LdapRecord: use `DirectoryEmulator::setup()` from `directorytree/ldaprecord-laravel`, or mock the
  `LdapUser` lookup. Never let an assertion depend on a real directory result.
- Oracle: mock the CDM/Livedata model or extract the logic so it can be tested against arrays
  (`StudentClass`-style helpers make this easy).
- Auth: `actingAs()` a factory user. Do not attempt to test the OIDC redirect round-trip — that is
  the package's responsibility.

## Existing tests

`tests/Feature/Auth/`, `tests/Feature/Filament/`, `tests/Unit/` already cover the template.
Several of them (`FilamentPanelAccessTest`, `UserResourceTest`) currently assert only structural
facts — class existence, configured navigation group, etc. — rather than exercising Livewire. Treat
those as placeholders: when you touch that area, upgrade them to real Livewire assertions using the
helpers above. **Do not delete existing tests without asking.**

Some assertions there also encode the *current* configuration (e.g. `UserResourceTest` expects
`getPages()` to have `create`/`edit` keys, which `UserResource` has commented out). If a test like
that fails, decide which side is right rather than blindly editing the assertion.
