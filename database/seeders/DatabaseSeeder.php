<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('shield:install app -n');

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
