<?php

namespace App\Http\Middleware;

use Closure;
use App\Setting;
use App\MenuItem;
use App\CustomClasses\MenuSingleton;

class CheckKillswitch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    protected $excludedRoutes = [
        'beheer/*',
        'login',
    ];

    public function handle($request, Closure $next)
    {
        if(app(Setting::SINGELTONNAME)->getsetting(Setting::SETTING_KILLSWITCH) && ! $request->is($this->excludedRoutes)) {
            $curPageName = trans('front-end/killswitch.title');

            return response()->view('errors.killswitch',compact('curPageName'), 404);
        }

        return $next($request);
    }
}
