<?php

namespace App\Http\Middleware;

use App\Enums\SettingKey;
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

    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::isEnabled(SettingKey::Killswitch) && ! $request->is($this->excludedRoutes)) {
            return response()->view('errors.killswitch', ['curPageName' => 'Website offline'], 404);
        }

        return $next($request);
    }
}
