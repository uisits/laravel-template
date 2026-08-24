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
