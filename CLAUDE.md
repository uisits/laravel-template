<laravel-boost-guidelines>
=== .ai/00-uis-project-overview rules ===

# UIS ITS Laravel Template — Project Overview

This repository is the **UIS ITS application template**. New University of Illinois Springfield
(UIS) ITS web applications are started by clicking "Use this template" on GitHub, not by running
`laravel new`. Everything already wired here (campus SSO, roles, Filament panel, Oracle
connections, Horizon/Telescope) is meant to be *kept and extended*, not replaced.

## What ships in the box

| Concern | Implementation |
| --- | --- |
| Admin/user UI | Filament v5 panel (`AppPanelProvider`), served at `/` |
| Authentication | Campus Shibboleth OIDC via `uisits/laravel-oidc` (UIS / UIC / UIUC) |
| Directory lookups | Active Directory via `directorytree/ldaprecord-laravel` (`App\Ldap\LdapUser`) |
| Authorization | `spatie/laravel-permission` + `bezhansalleh/filament-shield` |
| Institutional data | Oracle read connections via `yajra/laravel-oci8` (`config/oracle.php`) |
| Queues | Horizon (Redis) |
| Debugging | Telescope + `stephenjude/filament-debugger` panel plugin |
| Impersonation | `stechstudio/filament-impersonate` |
| Runtime | Octane (RoadRunner) behind a proxy |
| Formatting | Laravel Pint, enforced by GitHub Action |
| Tests | Pest v4 |
| AI tooling | Laravel Boost (MCP server + guidelines + skills) |

## Non-negotiables for agents

1. **Do not add or remove Composer/NPM packages without asking.** The template's dependency set is
   curated by ITS and shared across every downstream application.
2. **Do not create new top-level directories** under `app/` without approval. Use the existing
   structure (see `10-uis-architecture.md`).
3. **Do not replace the auth stack.** There is no login form, no registration, no password reset
   flow. Auth is campus SSO. See `20-uis-authentication.md`.
4. **Every permission-guarded thing goes through Shield.** Never hand-roll `Gate::define` for
   resources/pages/widgets. See `30-uis-authorization.md`.
5. **Run `vendor/bin/pint --dirty` before finishing.** Pint is the only formatter — Duster was
   removed from this template.
6. **Write or update a Pest test for every change**, then run only the affected tests with
   `php artisan test --compact --filter=...`.
7. **Use `search-docs` (Boost MCP) before writing framework/Filament code.** Versions here are new
   (Laravel 13, Filament 5, Livewire 4, Pest 4) and pre-training-cutoff memory is often wrong.

## Local commands

```bash
composer install && npm install
php artisan migrate
composer run dev
npm run build
php artisan test --compact
vendor/bin/pint --dirty
```

`composer run dev` runs serve + queue listener + Pail + Vite together. `npm run build` produces
production assets and is required after any theme/JS change.

Filament assets and the custom theme live under `resources/css/filament/app/`. Frontend changes are
invisible until `npm run dev` / `npm run build` runs — if a user reports "my change isn't showing",
ask which one they ran.

=== .ai/10-uis-architecture rules ===

# UIS ITS Architecture & Directory Conventions

## Directory map

| Path | Contents |
| --- | --- |
| `app/Filament/Pages/` | Panel pages — `Dashboard.php`, `Help.php` |
| `app/Filament/Resources/` | One folder per resource (layout below) |
| `app/Filament/Widgets/` | `WelcomeWidget.php` |
| `app/Helpers/` | Static domain helpers (`StudentClass.php`) **and** ops files |
| `app/Http/Controllers/` | Thin; today only overrides a vendor controller |
| `app/Ldap/` | LdapRecord models (`LdapUser.php`) |
| `app/Models/` | Eloquent models; institutional read models in `Models/Cdm/` and `Models/Livedata/` |
| `app/Policies/` | Shield-generated policies, one per model |
| `app/Providers/` | `AppServiceProvider`, `HorizonServiceProvider`, `TelescopeServiceProvider`, `Filament/AppPanelProvider` |

`app/Helpers/` holds **two unrelated kinds of file**: PHP helper classes *and* deployment/runtime
config used by the container image (`nginx.tmpl`, `octane.ini`, `horizon.ini`, `runonce.sh`,
`.bashrc`). Do not delete the non-PHP files; do not put new PHP classes anywhere that would collide
with them.

## Filament resource layout (v5, "split" style)

Filament v5's generator splits a resource into separate schema/table classes, and this template
follows that layout. Match it exactly:

- `Users/UserResource.php` — model, navigation, badges, `getPages()`; `form()`/`table()` delegate
- `Users/Pages/ListUsers.php` — header actions live here — plus `CreateUser.php`, `EditUser.php`
- `Users/Schemas/UserForm.php` — `public static function configure(Schema $schema): Schema`
- `Users/Tables/UserTable.php` — `public static function configure(Table $table): Table`

`UserResource::form()` / `::table()` are one-liners that delegate to those classes. Keep them that
way — never inline a 100-line form into the resource.

Generate with `php artisan make:filament-resource Thing --no-interaction` and check the produced
layout against `app/Filament/Resources/Users/` before editing.

## The panel

There is exactly **one** panel: `app`, defined in `app/Providers/Filament/AppPanelProvider.php`,
mounted at path `/` (so the dashboard is the site root). Key facts an agent needs:

- Resources, pages, and widgets are **auto-discovered** from `app/Filament/{Resources,Pages,Widgets}`.
  Do not also list them in `->pages([])` / `->widgets([])` — that double-registers them.
- Branding: Indigo primary / Slate gray, light default theme, full content width, collapsible
  sidebar, database notifications enabled, UIS logos from `resources/views/filament/logo/{light,dark}.blade.php`.
- Custom theme CSS: `resources/css/filament/app/theme.css` via `->viteTheme(...)`.
- Navigation group `Portal` is pre-declared. `UserResource` uses group `Settings`; the `Help` page
  uses group `Help`. Reuse existing group names rather than inventing near-duplicates.
- `register()` adds render hooks for the UIS footer (`resources/views/footer.blade.php`),
  `resources/js/app.js`, and the OneTrust cookie-consent script. `boot()` registers Google
  Analytics. **Leave the OneTrust and GA hooks alone** — they are university compliance/analytics
  requirements, not decoration.

## Providers

`bootstrap/providers.php` currently lists `AdminPanelProvider`, `StudentPanelProvider`, and
`HealthServiceProvider`, which do **not** exist in this branch — only `AppPanelProvider`,
`AppServiceProvider`, `HorizonServiceProvider`, and `TelescopeServiceProvider` do. The application
boots fine, but if you add a class with one of those names it will suddenly be registered. Treat
that file as stale and prune entries you are confident about rather than adding to the confusion.

`AppServiceProvider::boot()` establishes three template-wide behaviours — preserve them:

- `URL::forceScheme('https')` in production (the app runs behind a TLS-terminating proxy).
- `Model::preventLazyLoading(! app()->isProduction())` — **N+1s throw in local/testing.** Eager load.
- An `Http::placements()` macro for the ITS Placements API, configured from
  `config('services.placements.*')`. Follow this pattern (an `Http::macro` in `AppServiceProvider`)
  when integrating another campus API.

## Middleware & proxies

`bootstrap/app.php` trusts a fixed list of proxy IPs (Docker bridges, the campus load balancer,
localhost) and the full set of `X-Forwarded-*` headers. If request IPs or schemes look wrong in a
new environment, that list is the first place to check — but changing it is an infrastructure
decision, so ask before editing.

## Routing

`routes/web.php` contains exactly one route: a `POST logout` override named
`filament.app.auth.logout` pointing at `App\Http\Controllers\Filament\LogoutController`, which
redirects to the package's `GET /logout` so the OIDC single-logout runs. Everything else is either a
Filament panel route or a package route.

**Prefer Filament pages/resources over hand-written web routes.** If you genuinely need a web route,
name it and use `route()` for links. For APIs, use Eloquent API Resources and version the routes.

=== .ai/20-uis-authentication rules ===

# Authentication — Campus Shibboleth OIDC

**There is no local login form, registration, password reset, or email verification in UIS ITS
apps.** Users authenticate against the University of Illinois Shibboleth OIDC providers through
`uisits/laravel-oidc` (namespace `UisIts\Oidc`). Never scaffold Breeze/Jetstream/Fortify here, and
never add a `password` input to a form.

## The flow

Routes come from the package (`vendor/uisits/laravel-oidc/src/routes/routes.php`), not from
`routes/web.php`:

| Route | Name | Handler |
| --- | --- | --- |
| `GET /login` | `login` | `LoginAction` — redirects to the campus IdP |
| `GET /auth/callback` | `callback` | `CallbackHandleAction` — provisions the user, logs them in |
| `GET /logout` | `logout` | `LogoutAction` — IdP single logout |
| `GET/POST /tri-campus-discovery` | `tri-campus-discovery.{show,update}` | campus picker |

1. Filament's `Authenticate` middleware redirects guests to the `login` route.
2. With `'tri-campus-provider' => true` in `config/shibboleth-oidc.php`, the user first picks a
   campus at `/tri-campus-discovery`; the choice is stored in the session as `oidc.campus`.
3. `CallbackHandleAction` calls `Oidc::driver($campus)->user()` and does an
   `updateOrCreate(['uin' => ...], [...])` against `config('auth.providers.users.model')`.

**`uin` is the identity key.** It is the stable university-wide ID. `netid` and `email` change;
`uin` does not. Any lookup, join, or import of campus data keys on `uin`. `netid` and `email` are
stored lowercased by the callback — keep that invariant if you write your own provisioning code.

If the session is missing `oidc.campus`, the package throws `InvalidArgumentException('Campus not
set')`. Seeing that exception in logs usually means a session was lost (cookie domain, proxy, or
Octane state issue), not a code bug in your feature.

## Logout is two-step

Filament's own logout is a `POST`. `routes/web.php` overrides `filament.app.auth.logout` with
`App\Http\Controllers\Filament\LogoutController`, which simply `redirect('/logout')` so the
package's `LogoutAction` can terminate the IdP session too. If you ever change logout behaviour,
preserve this handoff or users will remain signed in at the IdP.

## Configuration

`config/shibboleth-oidc.php` defines three providers — `uis`, `uic`, `uiuc` — each with its own
client credentials, endpoints, scopes, and a `user-mapping` array. The mappings differ per campus
and that matters:

- UIS: `uin` ← `uisedu_uin`, groups ← `uisedu_is_member_of`, and it alone requests
  `address`, `phone`, `offline_access` scopes and maps `preferred_first_name`.
- UIC: `uin` ← `itrust_uin`, groups ← `is_member_of`, name ← `name`.
- UIUC: `uin` ← `itrust_uin`, groups ← `uiucedu_is_member_of`, name ← `full_name`.

Never hardcode a claim name in application code — read it through the mapping or through the
`User` model the package hands you. All secrets come from `.env` (`UIS_OIDC_*`, `UIC_OIDC_*`,
`UIUC_OIDC_*`, `*_INTROSPECT_*`); they are **not** in `.env.example`, so a fresh clone must get them
from ITS. Never commit them.

## The users table

The base migration creates a stock users table; the **package** migration
(`0001_01_01_000003_update_users_table.php`, loaded from the vendor directory, not published) adds
the campus columns:

`netid` (unique), `first_name`, `last_name`, `preferred_first_name` (nullable), `uin` (unique, 9),
`access_token`, `id_token`, `refresh_token`.

Because it is loaded from the package, you will not find it in `database/migrations/`. If you need
to change it, publish it with `php artisan vendor:publish --tag=shibboleth-migrations` rather than
writing a conflicting migration.

`App\Models\User` hides `password`, `remember_token`, `access_token`, `id_token`, `refresh_token`,
`token` from serialization and uses `$guarded = []`. **The OIDC tokens are secrets** — never add
them to a Filament table column, an infolist, an API resource, or a log line.

## Panel access

`User::canAccessPanel()` returns `true` in local, otherwise requires the email to end in
`@uis.edu`. Note this is a plain `str_ends_with`, so subdomain addresses like `x@sub.uis.edu` are
rejected. In a downstream app that must admit UIC/UIUC users, this method is the single place to
change — and it is a security boundary, so change it deliberately and add a test.

## Active Directory (LdapRecord)

`App\Ldap\LdapUser` wraps AD attributes with friendly accessors: `netid` ← `cn`,
`uin` ← `extensionattribute1`, `first_name` ← `givenname`, `last_name` ← `sn`,
`full_name` ← `displayname`, `email` ← `mail`, plus `title` and `department`.

Use it to provision or enrich users who have not logged in yet — see the "Create User from AD"
header action in `ListUsers` and `UserSeeder`. Always guard with `if ($adUser)` / `firstOrFail()`;
AD lookups miss regularly. LDAP credentials come from `LDAP_*` env vars, and **AD is unreachable in
tests and often from a laptop off-VPN** — never make a code path that non-AD-related tests must
traverse depend on a live LDAP query.

## API token introspection

For API endpoints, the package ships `UisIts\Oidc\Http\Middleware\Introspect`, which requires an
`Authorization: Bearer` header, calls the campus introspection endpoint, and optionally enforces
scopes passed as middleware parameters (`->middleware('introspect:openid,profile')`). It also
requires `oidc.campus` in the session. Sanctum is installed for first-party token needs; pick one
deliberately rather than mixing both on the same endpoint.

=== .ai/30-uis-authorization rules ===

# Authorization — Shield, Roles & Permissions

Authorization is `spatie/laravel-permission` driven by `bezhansalleh/filament-shield`. Permission
names are **generated**, not invented. Never write a permission string by hand and hope it matches.

## Permission naming

Shield generates `Action:Subject` in PascalCase, e.g. `ViewAny:User`, `Create:User`,
`Update:Role`, `View:Dashboard`, `View:WelcomeWidget`, `View:Help`. Resources get the full CRUD set
(`ViewAny, View, Create, Update, Delete, DeleteAny, Restore, RestoreAny, ForceDelete,
ForceDeleteAny, Replicate, Reorder`); pages and widgets get a single `View:` permission.

After adding **any** resource, page, or widget:

```bash
php artisan shield:generate --all --panel=app -n
```

Then grant the new permissions to the appropriate roles in `database/seeders/RoleSeeder.php`.
A feature that is not granted to a role is invisible to everyone except `super_admin`.

## Roles

Defined in `RoleSeeder` (guard `web`), which also runs `shield:install` and `shield:generate`:

| Role | Grants |
| --- | --- |
| `super_admin` | every permission (`Permission::all()`) |
| `admin` | `ViewAny:User`, `View:User`, `Update:User`, `View:Dashboard`, `View:WelcomeWidget`, `View:Help` |
| `panel_user` | `View:Dashboard`, `View:Help`, `View:WelcomeWidget` |

`panel_user` is Shield's baseline role, assigned automatically by the `HasPanelShield` trait on
`App\Models\User`. Any authenticated user therefore gets dashboard + help. Downstream apps add their
own roles here — extend `RoleSeeder` with a new `setupXxx()` method following the existing shape
(`Role::updateOrCreate([...])->givePermissionTo([...])`), so re-seeding stays idempotent.

`UserSeeder` bootstraps real maintainers by NetID via LdapRecord (super admins `tllos1`, `pchin3`,
`mari4`, `aayen3`; admins `kmcel2`, `vhube3`). **Downstream applications should edit these NetID
lists** to their own team. It requires a live AD connection.

## Policies

`app/Policies/` holds Shield-generated policies — one method per permission, each a single
`$authUser->can('Action:Subject')`. Generate them, do not write them by hand:

```bash
php artisan shield:generate --resource=ThingResource --panel=app -n
```

Note the signature: policy methods type-hint `Illuminate\Foundation\Auth\User as AuthUser`, not
`App\Models\User`. Match that when hand-editing, or Filament's authorization calls will fail.

If a policy needs record-level logic (owner-only editing, department scoping), add it *inside* the
generated method alongside the `can()` check — keep the permission gate and add your condition:

```php
public function update(AuthUser $authUser, Thing $thing): bool
{
    return $authUser->can('Update:Thing') && $thing->department_id === $authUser->department_id;
}
```

## Checking access in UI code

- Resources/pages/widgets: rely on the policy — Filament calls it automatically. Do not add a
  redundant `canView()`.
- Ad-hoc UI bits (a header action, a hidden column): `->visible(fn () => auth()->user()->hasRole('super_admin'))`
  or `->visible(fn () => auth()->user()->can('Update:Thing'))`, matching the pattern in `ListUsers`
  and in the `DebuggerPlugin` registration.
- Prefer permission checks (`can`) over role checks (`hasRole`) for feature gating; reserve
  `hasRole('super_admin')` for genuinely maintainer-only tools.

## Roles UI and impersonation

Shield's Roles resource is exposed at `/shield/roles` (nav group provided by the plugin), and
`stechstudio/filament-impersonate` adds an `Impersonate::make()` row action on the Users table.
Impersonation is a production support tool — keep it gated by the `super_admin` role or an explicit
permission if you extend it.

## Permission cache

`spatie/laravel-permission` caches for 24 hours (`config/permission.php`). After seeding or changing
permissions outside the UI, run `php artisan permission:cache-reset`. Note also
`'register_octane_reset_listener' => false` — if you observe stale permissions under Octane after a
change, that flag is the reason, and flipping it is a deliberate ops decision worth raising.

=== .ai/40-uis-filament-conventions rules ===

# Filament v5 Conventions (UIS ITS)

Read the Filament section of `CLAUDE.md`/`AGENTS.md` first for namespaces and API rules — this file
covers only what is specific to *this* template. Always confirm API details with `search-docs`
before writing Filament v5 code.

## Building a new resource — checklist

```bash
php artisan make:filament-resource Thing --generate --no-interaction
php artisan shield:generate --all --panel=app -n
```

Then:

1. Split the generated code into `Schemas/ThingForm.php` and `Tables/ThingTable.php` if the
   generator did not, and keep `ThingResource::form()/table()` as delegating one-liners.
2. Set `$navigationIcon`, `$navigationGroup`, `$navigationSort`, `$recordTitleAttribute`. Use the
   documented union property types:
   ```php
   protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
   protected static string|\UnitEnum|null $navigationGroup = 'Settings';
   ```
   On `Page`/`Widget` classes `$view` is `protected string`, **not** static.
3. Reuse an existing navigation group (`Portal`, `Settings`, `Help`, Shield's, Debugger's) instead
   of adding a near-duplicate.
4. Do not register the resource in `AppPanelProvider` — discovery handles it.
5. Add the new permissions to the right roles in `RoleSeeder`.
6. Write a Pest test (see `60-uis-testing.md`).
7. `vendor/bin/pint --dirty`.

## Patterns established by `UserResource`

Copy these rather than reinventing:

- **Global search**: `getGloballySearchableAttributes()` returning campus identifiers
  (`uin`, `netid`, names). Users search by NetID and UIN — make those searchable and `->copyable()`
  in tables.
- **Navigation badge**: `getNavigationBadge()` + `getNavigationBadgeColor()` for record counts. Only
  do this for cheap counts; a badge runs on every page render.
- **Trimmed pages**: `getPages()` in `UserResource` deliberately exposes only `index`, with
  `create`/`edit` commented out, because records are provisioned from AD/OIDC rather than typed in.
  When a resource's records come from an upstream system, follow the same approach instead of
  offering create/edit forms that would drift.
- **Roles on a form**: `CheckboxList::make('roles')->relationship('roles', 'name')->searchable()`.
- **Passwords**: never a visible field. `UserForm` uses
  `Hidden::make('password')->default(fn () => Hash::make(Str::random(5)))` — passwords are unused
  because auth is SSO, but the column is `NOT NULL`.
- **AD-backed creation**: the `create_user_from_ad` header action in `ListUsers` is the reference
  implementation for "pull a record from an upstream campus system" — a modal schema, a
  `firstOrFail()` lookup, an `updateOrCreate()`, a `->successNotification(...)`, and a
  `->visible(fn () => auth()->user()->hasRole('super_admin'))` guard.
- **Table defaults**: `->defaultSort(...)`, `->searchable()` on identifying columns,
  `->badge()->listWithLineBreaks()` for relation name lists, timestamps
  `->toggleable(isToggledHiddenByDefault: true)`.

## Pages and widgets

- Custom pages extend `Filament\Pages\Page` with `protected string $view`, and their Blade lives in
  `resources/views/filament/pages/`. `Help` is the template.
- The dashboard is overridden by `App\Filament\Pages\Dashboard` (extends `BaseDashboard`) with
  `WelcomeWidget` as a header widget and its own Blade view. Add app-specific dashboard widgets via
  `getHeaderWidgets()` / `getWidgets()` there, and give each a `View:` permission via Shield.
- Widgets live in `app/Filament/Widgets/` with `protected string $view` and
  `protected int|string|array $columnSpan`. `WelcomeWidget` overrides `canView(): bool { return
  true; }` — most widgets should *not* do that; let the Shield policy decide.

## Theming and branding

- Theme CSS: `resources/css/filament/app/theme.css` (Tailwind v4, `@tailwindcss/vite`). After
  editing, run `npm run build` (or `npm run dev`).
- Logos: `resources/views/filament/logo/{light,dark}.blade.php`. Keep the UIS marks; do not swap in
  generic branding.
- Colors are Indigo/Slate at the panel level — change them in `AppPanelProvider`, not with ad-hoc
  CSS overrides.
- The footer partial (`resources/views/footer.blade.php`), OneTrust consent script, and Google
  Analytics tag are injected via render hooks in `AppPanelProvider::register()/boot()`. They are
  university requirements — do not remove them while refactoring the provider.

## Debugging tools in the panel

`stephenjude/filament-debugger` exposes Telescope/Horizon inside the panel under the `Debugger`
navigation group, authorized with `fn () => auth()->user()->hasRole('super_admin')`. Pulse
navigation is currently disabled (`condition: fn () => false`). Any new debug/ops tool you surface
must carry the same `super_admin` guard.

=== .ai/50-uis-data-and-databases rules ===

# Data Sources, Databases & Institutional Data

## Connections

| Connection | Driver | Purpose |
| --- | --- | --- |
| default (`DB_CONNECTION`) | mysql / sqlite | The application's own tables |
| `oracle_cdm`, `oracle_cdm_pvt` | `yajra/laravel-oci8` (`config/oracle.php`) | Campus Data Mart — **read-only** |
| Redis | phpredis | Queues (Horizon), cache/session in production |
| LDAP | `config/ldap.php` | Active Directory lookups |

`config/oracle.php` defines the Oracle connections separately from `config/database.php` using
`DB_*_3` / `DB_*_4` env vars. They point at university systems of record.

**Rules for Oracle / institutional data:**

1. **Never write to them.** No inserts, updates, migrations, or `migrate:fresh` against an Oracle
   connection. They are reporting replicas owned by other units.
2. Models that read them go in `app/Models/Cdm/` or `app/Models/Livedata/` and must set
   `protected $connection` and `protected $table` explicitly (Oracle schemas do not follow Laravel's
   naming conventions), plus `public $timestamps = false` where the source table has none.
3. Columns use campus naming (`term_cd`, `crs_subj_cd`, `crs_nbr`, `crs_grade_cd`, `uin`). Preserve
   the source names in the model; translate to friendly names with accessors if needed.
4. Join application data to campus data on **`uin`**.
5. Oracle round-trips are slow. Select only the columns you need, eager load, and cache aggressive
   or repeated lookups. `Model::preventLazyLoading()` is on outside production, so an N+1 will throw
   in dev before it reaches an Oracle box in production.
6. The `oci8` PHP extension is often missing on developer laptops. Any feature that touches Oracle
   needs a code path (and tests) that do not require it — mock the model or fake the query.

`App\Helpers\StudentClass` is the reference implementation: static methods
(`getEnrolledClasses`, `getCompletedClasses`, `getTransferredCourses`) that take `$termCode` and
`$uin`, query a CDM model with explicit `select()`s and constrained eager loads, and return a
mapped `Collection` of plain course strings. Follow that shape — a small, typed, static query
helper — for new institutional data reads rather than scattering raw queries through Filament
classes. Note it also encodes real business rules (grade-code allow-lists, the `MAT 102` →
`ZZ_CSC302` equivalency); such rules belong in the helper, not in a UI component, and must be
covered by a unit test.

## Migrations for application tables

- Only the app's own tables get migrations. Present set: users/password resets/sessions, cache,
  jobs, Telescope entries, Spatie permission tables, notifications. The campus columns on `users`
  come from the OIDC package's migration (see `20-uis-authentication.md`).
- Standard Laravel practice applies: one concern per migration, explicit `down()`, index anything
  used in `WHERE`/`ORDER BY`/`JOIN`, and use foreign keys for app-owned relations. Do **not** add
  foreign keys pointing at Oracle-sourced identifiers.
- Store `uin` as a **string** (9 chars) — it can have leading zeros. Never cast it to an integer.
- Create factories and seeders alongside new models (`php artisan make:model Thing -mf`), and check
  `UserFactory` for the campus-field pattern (`netid`, `uin` via `numerify('#########')`,
  `first_name`/`last_name` composed into `name`).

## External HTTP APIs

Register a client as an `Http::macro` in `AppServiceProvider::boot()` with config-driven base URL
and token, exactly as `Http::placements()` does, and put credentials in `config/services.php` →
`.env`. Always set timeouts and handle failures explicitly; campus APIs go down.

## Secrets

`.env.example` is a stock Laravel file and does **not** list the UIS-specific variables
(`UIS_OIDC_*`, `UIC_OIDC_*`, `UIUC_OIDC_*`, `*_INTROSPECT_*`, `LDAP_*`, `DB_*_3`, `DB_*_4`,
`services.placements.*`). Real values come from ITS. When you add a new config key, add a
placeholder line to `.env.example` so the next developer knows it exists — never a real value.

=== .ai/60-uis-testing rules ===

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

=== .ai/70-uis-workflow-and-runtime rules ===

# Workflow, Tooling & Runtime (UIS ITS)

## Definition of done

1. Code follows the conventions in these guidelines and the sibling files it lives next to.
2. `php artisan shield:generate --all --panel=app -n` run if you added a resource/page/widget, and
   `RoleSeeder` updated.
3. A Pest test added or updated, and the affected tests pass (`php artisan test --compact --filter=...`).
4. `vendor/bin/pint --dirty` run.
5. Frontend touched? `npm run build`.
6. No new dependency, no new top-level directory, no new documentation file unless the user asked.

## Formatting & CI

**Laravel Pint is the formatter.** Tighten Duster was removed from this template — if you see a
`vendor/bin/duster` command in older docs, a downstream app, or your own memory of this repo, it is
stale. There is no `pint.json`, so Pint runs its default `laravel` preset.

```bash
vendor/bin/pint --dirty     # format what you changed — do this before finishing

vendor/bin/pint --test      # check without writing, as CI does

```

`.github/workflows/duster-fix.yml` still runs the `tighten/duster-action` on pull requests and
auto-commits the result, so **CI currently reformats with a tool the project no longer depends on**.
Its `push` trigger also still targets the old `11.x-filament` branch. That file needs to be replaced
with a Pint job — raise it rather than working around it. Dependabot maintains Composer/NPM bumps;
those PRs are routine.

Branch names in this repo track the stack version (`13.x`, `12.x-filament4`, …) rather than
`main`/`develop`. Check `git branch` before assuming a base branch.

## Laravel Boost

Boost is installed (`boost.json`) and configured for the `claude_code`, `cursor`, and `opencode`
agents, with guidelines enabled and skills `laravel-best-practices`, `configuring-horizon`,
`octane-development`, `pest-testing`, `tailwindcss-development` synced into `.claude/skills`,
`.cursor/skills`, and `.agents/skills`.

- **Use the MCP tools first**: `search-docs` (version-accurate docs — do this before writing
  framework code), `database-schema`, `database-query`, `list-artisan-commands`, `read-log-entries`,
  `last-error`, `browser-logs`, `get-absolute-url`.
- **Custom guidelines live in `.ai/guidelines/*.md`** (this directory). Boost concatenates them into
  `CLAUDE.md` / `AGENTS.md` on `php artisan boost:update`. Edit the files here — **never hand-edit
  `CLAUDE.md` or `AGENTS.md`**, your changes will be overwritten.
- **Custom skills live in `.ai/skills/<name>/SKILL.md`** and are mirrored into each agent's skills
  directory by Boost. See `.ai/skills/` for this project's own skills.

After changing anything in `.ai/`:

```bash
php artisan boost:update
```

## Runtime: Octane

Production runs **Octane on RoadRunner** (`OCTANE_SERVER=roadrunner`) behind a reverse proxy. The
application boots once and serves many requests, so:

- No request state in singletons or static properties. Use `$this->app->scoped()` for per-request
  services.
- Be careful with static caches on models/helpers — they persist across requests and users.
- `config('octane.server')` tells you the driver at runtime.
- Watch memory: long-lived workers amplify leaks that PHP-FPM would have hidden.
- Invoke the `octane-development` skill for concurrency, shared tables, and driver specifics.

Ops files for the container image live in `app/Helpers/`: `nginx.tmpl`, `octane.ini`, `horizon.ini`,
`runonce.sh`, `.bashrc`. Changing them changes deployment behaviour — confirm with the user first.

## Queues: Horizon

Redis-backed, configured in `config/horizon.php` and authorized via
`App\Providers\HorizonServiceProvider`. Access is surfaced through the Filament Debugger plugin for
`super_admin`s. New background work should be a queued job with sensible `$tries`/`$backoff` and a
tag; invoke the `configuring-horizon` skill when touching supervisors or balancing.

## Observability

- **Telescope** is enabled outside testing; `TELESCOPE_ENABLED=false` in `phpunit.xml`.
  `routes/console.php` prunes entries older than 72h daily at 02:00 and runs
  `OPTIMIZE TABLE telescope_entries` at 03:00. That optimize statement is **MySQL-specific** — if a
  downstream app uses a different default connection, that scheduled task will fail and needs
  adjusting.
- **Pail** (`php artisan pail`) for live logs; it is part of `composer run dev`.
- `/up` is the health endpoint (`bootstrap/app.php`).

## Scheduling

Define scheduled work in `routes/console.php` using `Schedule::command()` / `Schedule::call()`, with
`->name()` and `->withoutOverlapping()` on anything long-running, following the Telescope entries
already there.

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v13
- laravel/horizon (HORIZON) - v5
- laravel/octane (OCTANE) - v2
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/telescope (TELESCOPE) - v5
- livewire/livewire (LIVEWIRE) - v4
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- alpinejs (ALPINEJS) - v3
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== octane/core rules ===

# Laravel Octane

This application uses Laravel Octane, a long-running PHP server. The application bootstraps once and handles many requests within the same process.

- Never store request-specific state in singletons or static properties, because it can leak across requests.
- Use `config('octane.server')` to detect the active driver (`swoole`, `roadrunner`, or `frankenphp`).
- Prefer scoped bindings (`$this->app->scoped()`) over singletons for per-request services.

When working on Octane-specific features (concurrency, shared tables, memory, driver configuration, testing), invoke `octane-development` for detailed rules.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== filament/filament rules ===

## Filament

- Filament is a Laravel UI framework built on Livewire, Alpine.js, and Tailwind CSS. UIs are defined in PHP via fluent, chainable components. Follow existing conventions in this app.
- Use the `search-docs` tool for official documentation on Artisan commands, code examples, testing, relationships, and idiomatic practices. If `search-docs` is unavailable, refer to https://filamentphp.com/docs.

### Artisan

- Always use Filament-specific Artisan commands to create files. Find available commands with the `list-artisan-commands` tool, or run `php artisan --help`.
- Inspect required options before running, and always pass `--no-interaction`.

### Patterns

Always use static `make()` methods to initialize components. Most configuration methods accept a `Closure` for dynamic values.

Use `Get $get` to read other form field values for conditional logic:

<code-snippet name="Conditional form field visibility" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options(CompanyType::class)
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),

</code-snippet>

Use `Set $set` inside `->afterStateUpdated()` on a `->live()` field to mutate another field reactively. Prefer `->live(onBlur: true)` on text inputs to avoid per-keystroke updates:

<code-snippet name="Reactive field update" lang="php">
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

TextInput::make('title')
    ->required()
    ->live(onBlur: true)
    ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
        'slug',
        Str::slug($state ?? ''),
    )),

TextInput::make('slug')
    ->required(),

</code-snippet>

Compose layout by nesting `Section` and `Grid`. Children need explicit `->columnSpan()` or `->columnSpanFull()`:

<code-snippet name="Section and Grid layout" lang="php">
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

Section::make('Details')
    ->schema([
        Grid::make(2)->schema([
            TextInput::make('first_name')
                ->columnSpan(1),
            TextInput::make('last_name')
                ->columnSpan(1),
            TextInput::make('bio')
                ->columnSpanFull(),
        ]),
    ]),

</code-snippet>

Use `Repeater` for inline `HasMany` management. `->relationship()` with no args binds to the relationship matching the field name:

<code-snippet name="Repeater for HasMany" lang="php">
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        TextInput::make('institution')
            ->required(),
        TextInput::make('qualification')
            ->required(),
    ])
    ->columns(2),

</code-snippet>

Use `state()` with a `Closure` to compute derived column values:

<code-snippet name="Computed table column value" lang="php">
use Filament\Tables\Columns\TextColumn;

TextColumn::make('full_name')
    ->state(fn (User $record): string => "{$record->first_name} {$record->last_name}"),

</code-snippet>

Use `SelectFilter` for enum or relationship filters, and `Filter` with a `->query()` closure for custom logic:

<code-snippet name="Table filters" lang="php">
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

SelectFilter::make('status')
    ->options(UserStatus::class),

SelectFilter::make('author')
    ->relationship('author', 'name'),

Filter::make('verified')
    ->query(fn (Builder $query) => $query->whereNotNull('email_verified_at')),

</code-snippet>

Actions are buttons that encapsulate optional modal forms and behavior:

<code-snippet name="Action with modal form" lang="php">
use Filament\Actions\Action;

Action::make('updateEmail')
    ->schema([
        TextInput::make('email')
            ->email()
            ->required(),
    ])
    ->action(fn (array $data, User $record) => $record->update($data)),

</code-snippet>

### Testing

Testing setup (requires `pestphp/pest-plugin-livewire` in `composer.json`):

- Always call `$this->actingAs(User::factory()->create())` before testing panel functionality.
- For edit pages, pass `['record' => $user->id]`, use `->call('save')` (not `->call('create')`), and do not assert `->assertRedirect()` (edit pages do not redirect after save).

<code-snippet name="Table test" lang="php">
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->assertCanSeeTableRecords($users)
    ->searchTable($users->first()->name)
    ->assertCanSeeTableRecords($users->take(1))
    ->assertCanNotSeeTableRecords($users->skip(1));

</code-snippet>

<code-snippet name="Create resource test" lang="php">
use function Pest\Laravel\assertDatabaseHas;

livewire(CreateUser::class)
    ->fillForm([
        'name' => 'Test',
        'email' => 'test@example.com',
    ])
    ->call('create')
    ->assertNotified()
    ->assertHasNoFormErrors()
    ->assertRedirect();

assertDatabaseHas(User::class, [
    'name' => 'Test',
    'email' => 'test@example.com',
]);

</code-snippet>

<code-snippet name="Edit resource test" lang="php">
livewire(EditUser::class, ['record' => $user->id])
    ->fillForm(['name' => 'Updated'])
    ->call('save')
    ->assertNotified()
    ->assertHasNoFormErrors();

assertDatabaseHas(User::class, [
    'id' => $user->id,
    'name' => 'Updated',
]);

</code-snippet>

<code-snippet name="Testing validation" lang="php">
livewire(CreateUser::class)
    ->fillForm([
        'name' => null,
        'email' => 'invalid-email',
    ])
    ->call('create')
    ->assertHasFormErrors([
        'name' => 'required',
        'email' => 'email',
    ])
    ->assertNotNotified();

</code-snippet>

Use `->callAction(DeleteAction::class)` for page actions, or `->callAction(TestAction::make('name')->table($record))` for table actions:

<code-snippet name="Calling actions" lang="php">
use Filament\Actions\Testing\TestAction;

livewire(ListUsers::class)
    ->callAction(TestAction::make('promote')->table($user), [
        'role' => 'admin',
    ])
    ->assertNotified();

</code-snippet>

### Correct Namespaces

- Form fields (`TextInput`, `Select`, `Repeater`, etc.): `Filament\Forms\Components\`
- Infolist entries (`TextEntry`, `IconEntry`, etc.): `Filament\Infolists\Components\`
- Layout components (`Grid`, `Section`, `Fieldset`, `Tabs`, `Wizard`, etc.): `Filament\Schemas\Components\`
- Schema utilities (`Get`, `Set`, etc.): `Filament\Schemas\Components\Utilities\`
- Table columns (`TextColumn`, `IconColumn`, etc.): `Filament\Tables\Columns\`
- Table filters (`SelectFilter`, `Filter`, etc.): `Filament\Tables\Filters\`
- Actions (`DeleteAction`, `CreateAction`, etc.): `Filament\Actions\`. Never use `Filament\Tables\Actions\`, `Filament\Forms\Actions\`, or any other sub-namespace for actions.
- Icons: `Filament\Support\Icons\Heroicon` enum (e.g., `Heroicon::PencilSquare`)

### Common Mistakes

- **Never assume public file visibility.** File visibility is `private` by default. Always use `->visibility('public')` when public access is needed.
- **Never assume full-width layout.** `Grid`, `Section`, `Fieldset`, and `Repeater` do not span all columns by default.
- **Use `Select::make('author_id')->relationship('author', 'name')` for BelongsTo fields.** `BelongsToSelect` does not exist in v4.
- **`Repeater` uses `->schema()`, not `->fields()`.**
- **Never add `->dehydrated(false)` to fields that need to be saved.** It strips the value from form state before `->action()` or the save handler runs. Only use it for helper/UI-only fields.
- **Use correct property types when overriding `Page`, `Resource`, and `Widget` properties.** These properties have union types or changed modifiers that must be preserved:
  - `$navigationIcon`: `protected static string | BackedEnum | null` (not `?string`)
  - `$navigationGroup`: `protected static string | UnitEnum | null` (not `?string`)
  - `$view`: `protected string` (not `protected static string`) on `Page` and `Widget` classes

</laravel-boost-guidelines>
