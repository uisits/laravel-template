---
name: uis-campus-integrations
description: "Use this skill for any work touching University of Illinois campus systems in a UIS ITS Laravel application: Shibboleth OIDC login/logout/callback via uisits/laravel-oidc, tri-campus (UIS/UIC/UIUC) provider selection and claim mapping, user provisioning from OIDC or Active Directory, LdapRecord/LdapUser directory lookups, Oracle Campus Data Mart reads via yajra/laravel-oci8 (Models\\Cdm, Models\\Livedata), UIN/NetID identity handling, API token introspection, and external campus HTTP APIs registered as Http macros. Triggers on 'login is broken', 'campus not set', 'look up a user in AD', 'pull student/course data', 'add an Oracle connection', or panel-access rules. Do not use for ordinary Filament UI work — use uis-filament-feature for that."
license: MIT
metadata:
  author: UIS ITS
---

# Campus integrations (OIDC, Active Directory, Oracle CDM)

## Identity: `uin` is the key

`uin` is the stable 9-character university-wide ID. `netid` and `email` change over a person's
lifecycle; `uin` does not. Every `updateOrCreate`, join, and cross-system lookup keys on `uin`, and
it is stored as a **string** (leading zeros are meaningful — never cast it to an integer).
`netid` and `email` are persisted lowercased; keep that invariant.

## Authentication (uisits/laravel-oidc)

There is **no local login form, registration, or password reset.** Never scaffold one.

Package routes (`vendor/uisits/laravel-oidc/src/routes/routes.php`):
`GET /login` → `LoginAction`, `GET /auth/callback` → `CallbackHandleAction`,
`GET /logout` → `LogoutAction`, `GET|POST /tri-campus-discovery` → `TriCampusHandler`.

Flow: Filament's `Authenticate` middleware sends guests to `login`; with
`'tri-campus-provider' => true` the user first picks a campus, which is stored in the session as
`oidc.campus`; the callback then calls `Oidc::driver($campus)->user()` and does
`updateOrCreate(['uin' => ...], [...])` on `config('auth.providers.users.model')` before
`Auth::login()`.

Debugging notes:

- `InvalidArgumentException: Campus not set` means the session lost `oidc.campus` — look at session
  driver/cookie domain, the trusted-proxy list in `bootstrap/app.php`, or Octane state leakage
  before suspecting the package.
- Logout is two hops on purpose: `POST logout` (`filament.app.auth.logout`) is overridden by
  `App\Http\Controllers\Filament\LogoutController`, which redirects to the package's `GET /logout`
  so the IdP session ends too. Preserve that handoff.
- Claim names differ per campus and live in `config/shibboleth-oidc.php` under each provider's
  `user-mapping` (UIS: `uisedu_uin` / `uisedu_is_member_of`; UIC: `itrust_uin` / `is_member_of`;
  UIUC: `itrust_uin` / `uiucedu_is_member_of`). Never hardcode a claim name in application code.
- The campus columns on `users` (`netid`, `first_name`, `last_name`, `preferred_first_name`, `uin`,
  `access_token`, `id_token`, `refresh_token`) come from a migration **inside the package**, not
  `database/migrations/`. To change them, publish it:
  `php artisan vendor:publish --tag=shibboleth-migrations`.
- `access_token` / `id_token` / `refresh_token` are secrets: hidden on the model, and never to be
  surfaced in a table column, infolist, API resource, notification, or log line.
- Panel access is decided by `User::canAccessPanel()` — `true` in local, otherwise
  `str_ends_with($this->email, '@uis.edu')` (which rejects subdomain addresses). This is a security
  boundary: change it deliberately and add a test.
- API endpoints can use `UisIts\Oidc\Http\Middleware\Introspect` (requires a Bearer token and
  `oidc.campus` in session; accepts required scopes as middleware parameters). Sanctum is also
  available — pick one per endpoint, don't mix.

## Active Directory (LdapRecord)

`App\Ldap\LdapUser` maps AD attributes: `netid`←`cn`, `uin`←`extensionattribute1`,
`first_name`←`givenname`, `last_name`←`sn`, `full_name`←`displayname`, `email`←`mail`, plus `title`
and `department`.

```php
$adUser = LdapUser::where('cn', $netid)->first();          // or ->firstOrFail()
$adUser = LdapUser::where('extensionattribute1', $uin)->first();
```

Always guard for a miss — AD lookups fail often. Use AD to provision or enrich users who have not
logged in yet (`UserSeeder`, and the `create_user_from_ad` action in `ListUsers` are the reference
implementations). AD is unreachable from CI and from laptops off-VPN, so never put a live LDAP call
on a path that unrelated tests must traverse; mock it or use `DirectoryEmulator`.

## Oracle Common Data Model

Connections `oracle_cdm` and `oracle_cdm_pvt` are defined in `config/oracle.php` (env `DB_*_3`,
`DB_*_4`) using `yajra/laravel-oci8`.

**These are read-only replicas owned by other university units. Never insert, update, migrate, or
run `migrate:fresh` against them.**

Model conventions — put them in `app/Models/Cdm/` or `app/Models/Livedata/`:

```php
class StudentGradeHistory extends Model
{
    protected $connection = 'oracle_cdm';
    protected $table = 'SCHEMA.TABLE_NAME';
    public $timestamps = false;
}
```

Keep source column names (`term_cd`, `crs_subj_cd`, `crs_nbr`, `crs_grade_cd`, `uin`); expose
friendly names through accessors if needed.

Query rules: explicit `select()` of only the needed columns, constrained eager loads, cache repeated
lookups. `Model::preventLazyLoading()` is enabled outside production, so N+1s throw in dev before
they can hammer an Oracle box in production.

`App\Helpers\StudentClass` is the pattern to follow — small static methods taking `(string $termCode,
string $uin)`, returning mapped Collections, with business rules (grade-code allow-lists, course
equivalencies like `MAT 102` → `ZZ_CSC302`) encoded in the helper rather than in UI code. Cover
those rules with unit tests that don't require a database.

The `oci8` PHP extension is frequently missing on developer machines — features touching Oracle must
have a test path that doesn't need it.

## External campus HTTP APIs

Register a configured client as an `Http` macro in `AppServiceProvider::boot()`:

```php
Http::macro('placements', function () {
    return Http::baseUrl(config('services.placements.base_url'))
        ->withToken(config('services.placements.token'));
});
```

Put credentials in `config/services.php` → `.env`, add a placeholder line to `.env.example`, set
timeouts, and handle failures explicitly. In tests use `Http::fake()` and
`Http::preventStrayRequests()`.

## Secrets

`UIS_OIDC_*`, `UIC_OIDC_*`, `UIUC_OIDC_*`, `*_INTROSPECT_*`, `LDAP_*`, `DB_*_3`, `DB_*_4` and API
tokens come from ITS and are absent from `.env.example`. Never commit real values; never print them
in logs, notifications, or test output.
