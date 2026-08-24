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
