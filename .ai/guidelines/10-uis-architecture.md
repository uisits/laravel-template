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
