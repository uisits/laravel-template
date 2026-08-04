<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('shield:install app -n');
        Artisan::call('shield:generate --all --panel=app -n');

        $this->setupSuperAdmin();

        $this->setupPanelUser();

        $this->setupAdmin();
    }

    protected function setupSuperAdmin(): void
    {
        $role = Role::updateOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());
    }

    private function setupAdmin(): void
    {
        $role = Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo([
            'ViewAny:User', 'View:User', 'Update:User',
            'View:Dashboard', 'View:WelcomeWidget', 'View:Help',
        ]);
    }

    protected function setupPanelUser(): void
    {
        $role = Role::updateOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);
        $role->givePermissionTo([
            'View:Dashboard', 'View:Help', 'View:WelcomeWidget',
        ]);
    }
}
