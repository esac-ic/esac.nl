<?php

namespace App\Http\Controllers\Auth;

use App\CustomClasses\MenuSingleton;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
        $this->_menu = $menuSingleton;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $menu = $this->_menu;
        return view('auth.login', compact('menu'));
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, UserRepository $userRepository)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $users = $userRepository->findBy("email", $request[$this->username()]);
        $user = null;
        if (count($users) != 0) {
            $user = $users[0];
        }
        if ($user != null && $user->isOldMember()) {
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([$this->username() => 'Old members can not login']);
        } else if ($user != null && $user->isPendingMember()) {
            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([$this->username() => 'Pending members can not login']);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
