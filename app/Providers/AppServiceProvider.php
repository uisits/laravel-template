<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Model::preventLazyLoading(! $this->app->isProduction());

        Http::macro('placements', function () {
            $client = Http::baseUrl(config('services.placements.base_url'))
                ->withToken(config('services.placements.token'));

            return $client;
        });
    }
}
