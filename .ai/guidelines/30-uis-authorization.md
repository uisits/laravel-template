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
