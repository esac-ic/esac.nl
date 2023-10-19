<?php

namespace App\Providers;

use App\CustomClasses\MenuSingleton;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MenuSingleton', function ($app) {
            return new MenuSingleton();
        });
    }
}
