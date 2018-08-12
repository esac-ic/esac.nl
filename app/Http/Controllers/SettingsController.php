<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Session\Session;

class SettingsController extends Controller
{
    private $_LANGSESSIONNAME = "lang";
    public function setLanguage(){
        $language = Input::get('language','nl');
        $session = new Session();
        $session->set($this->_LANGSESSIONNAME,$language);
        return "";
    }

}
