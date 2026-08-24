<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\AppPanelProvider;
use App\Providers\Filament\StudentPanelProvider;
use App\Providers\HealthServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    AppPanelProvider::class,
    StudentPanelProvider::class,
    HealthServiceProvider::class,
    HorizonServiceProvider::class,
    TelescopeServiceProvider::class,
];
