<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'HusamTariq\FilamentDatabaseSchedule\Models\Schedule' => 'App\Policies\SchedulePolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user)
        {
            return $user->hasRole('admin');
        });

        Gate::define('super_admin', function (User $user)
        {
            return $user->hasRole('super_admin');
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->can('super_admin');
        });
    }
}
