<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations for tests
        // Artisan::call('shield:install app -n');
        // Artisan::call('shield:generate --all --panel=app -n');
        Artisan::call('db:seed', ['--class' => RoleSeeder::class]);
    }
}
