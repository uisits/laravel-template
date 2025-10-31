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

        // Developers
        collect([
            'tllos1', 'kmcel2', 'pchin3', 'vhube3', 'mari4', 'aayen3',
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
                Artisan::call('shield:super-admin --panel=app -n --user=' . $user->id);
            }
        });
    }
}
