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
