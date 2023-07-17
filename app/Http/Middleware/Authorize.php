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
    public function handle($request, Closure $next, $role,$role2="-1",$role3 = "-1")
    {
        if(Auth::user()->hasRole($role,$role2,$role3)){
            return $next($request);
        } else {
            abort(403, trans('validation.Unauthorized'));
        }

    }
}
