<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;

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
        if (app(Setting::SINGELTONNAME)->getsetting(Setting::SETTING_KILLSWITCH) && !$request->is($this->excludedRoutes)) {
            $curPageName = 'Website offline';

            return response()->view('errors.killswitch', compact('curPageName'), 404);
        }

        return $next($request);
    }
}
