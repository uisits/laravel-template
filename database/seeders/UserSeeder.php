<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'first_name' => 'Prasad',
                'last_name' => 'Chinwal',
                'uin' => '658819123',
                'netid' => 'pchin3',
                'email' => 'pchin3@uis.edu',
            ],
            [
                'first_name' => 'Kara',
                'last_name' => 'McElwrath',
                'uin' => '665154621',
                'netid' => 'kmcel2',
                'email' => 'kmcel2@uis.edu',
            ],
            [
                'first_name' => 'Monica',
                'last_name' => 'Kroft',
                'uin' => '671508683',
                'netid' => 'mkrof2',
                'email' => 'mkrof2@uis.edu',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Abrell',
                'uin' => '679720141',
                'netid' => 'jabre2',
                'email' => 'jabre2@uis.edu',
            ]
        ])->each(function ($user) {
            $user = User::updateOrCreate([
                    'uin' => $user['uin']
                ],
                $user
            );
            $user->assignRole('admin');
        });
    }
}
