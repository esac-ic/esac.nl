<?php

namespace App\Http\Controllers;

use App\Enums\MemberType;
use App\Http\Requests\AccountRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * The logged-in member's own account.
 */
class AccountController extends Controller
{
    public function show(Request $request): View
    {
        return view('account.show', [
            'user' => $request->user()->load('certificates', 'registrations.event'),
            'pageTitle' => 'Account overview',
        ]);
    }

    public function edit(Request $request): View
    {
        return view('account.edit', [
            'user' => $request->user(),
            'memberTypes' => MemberType::options(),
            'pageTitle' => 'Edit account',
        ]);
    }

    public function update(AccountRequest $request): RedirectResponse
    {
        $request->user()->updateFromForm($request->safe()->except('current_password'));

        return to_route('account.show');
    }
}
