<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Session\Session;

class Language
{
    private $_LANGSESSIONNAME = "lang";
    private $_LANGLOOKUP = [
        "nl"    =>  "nl",
        "en"    =>  "en",
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
        $session = new Session();
        $language = $session->get($this->_LANGSESSIONNAME,'nl');
        if(array_key_exists($language,$this->_LANGLOOKUP)){
            \App::setLocale($this->_LANGLOOKUP[$language]);
        }

        return $next($request);
    }
}
