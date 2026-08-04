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
