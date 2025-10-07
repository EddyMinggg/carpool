<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind VonageSmsService as singleton
        $this->app->singleton(\App\Services\VonageSmsService::class, function ($app) {
            return new \App\Services\VonageSmsService();
        });

        // Bind OtpService with VonageSmsService dependency
        $this->app->singleton(\App\Services\OtpService::class, function ($app) {
            return new \App\Services\OtpService($app->make(\App\Services\VonageSmsService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
    }
}
