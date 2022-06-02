<?php

namespace App\Providers;

use DB;
use App\ApplicationResponse;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use App\CustomClasses\MenuSingleton;
use App\Observers\ApplicationResponseObserver;
use App\Setting;
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
        $this->app->singleton(MenuSingleton::MENUSINGLETON, function()
        {
            return new MenuSingleton();
        });

        $this->app->singleton(Setting::SINGELTONNAME, function()
        {
            $setting = new Setting();
            $setting->initialise();
            return $setting;
        });

        Blueprint::macro('dropForeignSilently', function($index): Fluent {
            if (DB::getDriverName() === 'sqlite') return new Fluent();
            return self::dropForeign($index);
        });
    }
}
