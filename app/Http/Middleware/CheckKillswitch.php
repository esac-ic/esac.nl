<?php

namespace App\Http\Middleware;

use Closure;
use App\Setting;
use App\MenuItem;
use App\CustomClasses\MenuSingleton;

class CheckKillswitch
{
    protected $excludedRoutes = [
        'beheer/*',
        'login',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if(app(Setting::SINGELTONNAME)->getsetting(Setting::SETTING_KILLSWITCH) && ! $request->is($this->excludedRoutes)) {
            $curPageName = ('front-end/killswitch.title');

            return response()->view('errors.killswitch',compact('curPageName'), 404);
        }

        return $next($request);
    }
}
