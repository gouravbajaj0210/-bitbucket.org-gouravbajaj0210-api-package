<?php

namespace gouravbajaj0210\api;

use Illuminate\Support\ServiceProvider;

class ApiProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
        __DIR__.'/config/api.php' => config_path('api.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('api', function () {
            return $this->app->make('mandeep03\api\ApiResponse');
        });

        // $this->mergeConfigFrom(
        // __DIR__.'/config/api.php', 'api'
        // );
    }
}
