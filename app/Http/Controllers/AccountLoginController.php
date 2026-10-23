<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountLoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountLoginController extends Controller
{
    public function edit(Request $request): View
    {
        return view('account.login', [
            'user' => $request->user(),
            'pageTitle' => 'Email and password',
        ]);
    }

    public function update(AccountLoginRequest $request): RedirectResponse
    {
        $request->user()->updateFromForm($request->safe()->except('current_password'));

        return to_route('account.show')->with('message', 'Your email address and password are saved.');
    }
}
