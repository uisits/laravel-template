<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Developers
        collect([
            'tllos1', 'kmcel2', 'pchin3', 'vhube3', 'mari4', 'aayen3'
        ])->each(function (string $netid) {
            $adUser = \App\Ldap\User::where('cn', $netid)->first();
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
                $user->assignRole('panel_user');
            }
        });
    }
}
