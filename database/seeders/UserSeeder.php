<?php

namespace Database\Seeders;

use App\Ldap\LdapUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artisan::call('shield:generate --panel=app -n --all');
        $this->superAdmins();
        $this->admin();
    }

    private function superAdmins(): void
    {
        // Super-Admins or Maintainers
        collect([
            'tllos1', 'pchin3', 'mari4', 'aayen3',
        ])->each(function (string $netid) {
            $adUser = LdapUser::where('cn', $netid)->first();
            if ($adUser) {
                $user = User::updateOrCreate(
                    [
                        'uin' => $adUser->uin,
                    ],
                    [
                        'netid' => $adUser->netid,
                        'uin' => $adUser->uin,
                        'name' => $adUser->full_name,
                        'first_name' => $adUser->first_name,
                        'last_name' => $adUser->last_name,
                        'email' => $adUser->email,
                        'password' => Hash::make('P@ssw0rd'),
                    ]
                );
                $user->assignRole('super_admin');
            }
        });
    }

    private function admin(): void
    {
        // Admin - application admins
        collect([
            'kmcel2', 'vhube3',
        ])->each(function (string $netid) {
            $adUser = LdapUser::where('cn', $netid)->first();
            if ($adUser) {
                $adminUser = User::create([
                    'uin' => $adUser->uin,
                    'netid' => $adUser->netid,
                    'name' => $adUser->full_name,
                    'first_name' => $adUser->first_name,
                    'last_name' => $adUser->last_name,
                    'email' => $adUser->email,
                    'password' => Hash::make('P@ssw0rd'),
                ]);
                $adminUser->assignRole('admin');
            }
        });
    }
}
