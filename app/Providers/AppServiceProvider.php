<?php

namespace App\Providers;

use App\CustomClasses\MenuSingleton;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MenuSingleton::MENUSINGLETON, function () {
            return new MenuSingleton();
        });

        $this->app->singleton(Setting::SINGELTONNAME, function () {
            $setting = new Setting();
            $setting->initialise();
            return $setting;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');
    }
}
