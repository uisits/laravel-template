---
name: uis-filament-feature
description: "Use this skill when adding or modifying any user-facing feature in a UIS ITS Laravel template application — Filament resources, custom panel pages, dashboard widgets, table columns, form schemas, header/row/bulk actions, or navigation. Triggers on requests like 'add a CRUD screen for X', 'add a page to the panel', 'add a widget to the dashboard', 'add a filter/column/action', and on any work that needs new permissions wired through Filament Shield and RoleSeeder. Covers the split resource layout (Resource + Schemas + Tables + Pages), the shield:generate step, role grants, and the matching Pest tests. Do not use for authentication, LDAP, or Oracle data-source work — use uis-campus-integrations for those."
license: MIT
metadata:
  author: UIS ITS
---

# Building a Filament feature in a UIS ITS application

This template has exactly one panel (`app`, mounted at `/`). Features are Filament resources, pages,
or widgets — not hand-written controllers and Blade routes.

Before writing code, call `search-docs` for the Filament v5 / Laravel 13 APIs you are about to use.
Then look at `app/Filament/Resources/Users/` — it is the reference implementation for everything
below.

## Order of operations

Do these in order. Skipping step 3 or 4 produces a feature nobody can see.

### 1. Generate, don't hand-write

```bash
php artisan make:filament-resource Thing --generate --no-interaction
# or
php artisan make:filament-page ThingReport --panel=app --no-interaction
php artisan make:filament-widget ThingStats --panel=app --no-interaction
```

### 2. Match the split layout

```
app/Filament/Resources/Things/
├── ThingResource.php          # config + one-line form()/table() delegates
├── Pages/{List,Create,Edit}Thing.php
├── Schemas/ThingForm.php      # public static function configure(Schema $schema): Schema
└── Tables/ThingTable.php      # public static function configure(Table $table): Table
```

Resource property types (v5 unions — using `?string` will fatal):

```php
protected static ?string $model = Thing::class;
protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
protected static string|\UnitEnum|null $navigationGroup = 'Portal';
protected static ?int $navigationSort = 3;
protected static ?string $recordTitleAttribute = 'name';
```

On `Page` and `Widget` classes, `$view` is `protected string $view` — **not static**.

Do **not** register the class in `AppPanelProvider`; `discoverResources/Pages/Widgets` finds it.
Reuse an existing navigation group (`Portal`, `Settings`, `Help`) instead of adding a synonym.

### 3. Generate permissions

```bash
php artisan shield:generate --all --panel=app -n
```

This creates `ViewAny:Thing`, `Create:Thing`, … and `app/Policies/ThingPolicy.php`. Never invent
permission strings by hand. Policy methods type-hint
`Illuminate\Foundation\Auth\User as AuthUser`.

Record-level rules go inside the generated method, keeping the permission check:

```php
public function update(AuthUser $authUser, Thing $thing): bool
{
    return $authUser->can('Update:Thing') && $thing->owner_uin === $authUser->uin;
}
```

### 4. Grant the permissions

Edit `database/seeders/RoleSeeder.php` — add the new permissions to `admin` and/or `panel_user`
(`super_admin` gets everything automatically). Keep it idempotent
(`Role::updateOrCreate(...)->givePermissionTo([...])`).

### 5. Test it

`tests/Pest.php` gives you `asSuperAdmin()`, `asAdmin()`, `asPanelUser()`,
`createUserWithPermissions([...])`, and `RoleSeeder` runs before every test, so all Shield
permissions already exist.

```php
use function Pest\Livewire\livewire;

it('lets an admin create a thing', function () {
    asAdmin();

    livewire(CreateThing::class)
        ->fillForm(['name' => 'Test'])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Thing::class, ['name' => 'Test']);
});

it('hides things from a plain panel user', function () {
    asPanelUser();

    livewire(ListThings::class)->assertForbidden();
});
```

Always write the negative authorization case too. Run
`php artisan test --compact tests/Feature/Filament/ThingResourceTest.php`.

### 6. Format

```bash
vendor/bin/pint --dirty
```

Pint is the only PHP formatter in this template. If you touched CSS/JS, run `npm run build`.

## House patterns to copy

- Make `uin` and `netid` `->searchable()` and `->copyable()`; add identifying fields to
  `getGloballySearchableAttributes()`.
- Relation name lists: `TextColumn::make('roles.name')->badge()->listWithLineBreaks()`.
- Timestamps: `->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true)`.
- Navigation count badge via `getNavigationBadge()` — only when the count is cheap.
- Maintainer-only affordances: `->visible(fn () => auth()->user()->hasRole('super_admin'))`.
  Prefer `->can('Action:Thing')` for ordinary feature gating.
- Actions with modals use `->schema([...])->action(fn (array $data) => ...)` and a
  `->successNotification(Notification::make()->success()->title(...)->body(...))`.
- If a resource's records are provisioned from an upstream system (AD, OIDC, CDM), omit the
  `create`/`edit` pages from `getPages()` and provide an explicit import action instead — see
  `create_user_from_ad` in `ListUsers`.

## Things not to do

- Don't add a login form, password field, or registration UI — auth is campus SSO.
- Don't display `access_token`, `id_token`, `refresh_token`, or `password` anywhere.
- Don't remove the footer / OneTrust / Google Analytics render hooks in `AppPanelProvider`.
- Don't add packages, top-level directories, or documentation files without asking.
