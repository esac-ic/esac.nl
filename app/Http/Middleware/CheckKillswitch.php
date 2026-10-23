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
        // The admin pages, the API for admins and Fortify's login, logout and password routes stay available.
        $excluded = $request->is('admin', 'admin/*')
            || ($request->is('api/*') && $request->user()?->can('access-admin'))
            || $request->routeIs('login', 'login.*', 'logout', 'password.*');

        if (! $excluded && Setting::isEnabled(SettingKey::Killswitch)) {
            return response()->view('errors.killswitch', ['pageTitle' => 'Website offline'], 404);
        }

        return $next($request);
    }
}
