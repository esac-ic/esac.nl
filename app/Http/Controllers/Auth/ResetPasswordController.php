<?php

namespace App\Http\Controllers\Auth;

use App\CustomClasses\MenuSingleton;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';
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
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        $menu = $this->_menu;
        return view('auth.passwords.reset', compact('menu'))->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
