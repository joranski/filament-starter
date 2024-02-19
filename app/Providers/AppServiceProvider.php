<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerSingletons();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        if (app()->environment('production') || str_ends_with(config('app.domain'), '.com')) {
            URL::forceScheme('https');
        }
    }

    private function registerSingletons()
    {
        // $this->app->singleton('settings', function () {
        //     return new \App\Settings\MainSettings();
        // });
        // $this->app->singleton('pubsub', function () {
        //     return new \App\Services\Google\PubSubService();
        // });
    }
}