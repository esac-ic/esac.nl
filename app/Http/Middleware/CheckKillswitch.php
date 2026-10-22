<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKillswitch
{
    protected array $excludedRoutes = [
        'beheer/*',
        'login',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app(Setting::SINGELTONNAME)->getsetting(Setting::SETTING_KILLSWITCH) && !$request->is($this->excludedRoutes)) {
            $curPageName = 'Website offline';

            return response()->view('errors.killswitch', compact('curPageName'), 404);
        }

        return $next($request);
    }
}
