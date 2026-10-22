<?php

namespace App\Providers;

use App\CustomClasses\MenuSingleton;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(MenuSingleton::MENUSINGLETON, fn () => new MenuSingleton);

        $this->app->scoped(Setting::SINGELTONNAME, function () {
            $setting = new Setting;
            $setting->initialise();

            return $setting;
        });
    }
}
