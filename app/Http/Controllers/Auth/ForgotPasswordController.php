<?php

namespace App\Http\Controllers\Auth;

use App\CustomClasses\MenuSingleton;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */

    use SendsPasswordResetEmails;

    private $_menu;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MenuSingleton $menuSingleton)
    {
        $this->middleware('guest');
        $this->_menu = $menuSingleton;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $menu = $this->_menu;
        return view('auth.passwords.email', compact('menu'));
    }
}
