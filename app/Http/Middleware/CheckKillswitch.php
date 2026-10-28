<?php

namespace App\Http\Middleware;

use App\Enums\SettingKey;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKillswitch
{
    public function handle(Request $request, Closure $next): Response
    {
        // The admin pages and Fortify's login, logout and password routes stay available.
        $excluded = $request->is('beheer/*') || $request->routeIs('login', 'login.*', 'logout', 'password.*');

        if (! $excluded && Setting::isEnabled(SettingKey::Killswitch)) {
            return response()->view('errors.killswitch', ['curPageName' => 'Website offline'], 404);
        }

        return $next($request);
    }
}
