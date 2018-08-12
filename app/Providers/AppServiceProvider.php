<?php

namespace App\Providers;

use App\ApplicationResponse;
use App\CustomClasses\MenuSingleton;
use App\Observers\ApplicationResponseObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('recaptcha','App\\Validators\\ReCaptcha@validate');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mailgun.client', function() {
            //todo set correct config values
            return \Http\Adapter\Guzzle6\Client::createWithConfig(array());
        });

        $this->app->singleton(MenuSingleton::MENUSINGLETON, function()
        {
            return new MenuSingleton();
        });
    }
}
