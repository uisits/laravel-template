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
