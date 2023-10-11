<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $role2 = "-1")
    {
        if (Auth::user()->hasRole($role, $role2)) {
            return $next($request);
        } else {
            abort(403, 'You do not have sufficient access to view this page');
        }

    }
}
