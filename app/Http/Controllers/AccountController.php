<?php

namespace App\Http\Controllers;

use App\Enums\MemberType;
use App\Http\Requests\AccountRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'pageTitle' => 'Edit your details',
        ]);
    }

    public function update(AccountRequest $request): RedirectResponse
    {
        $request->user()->updateFromForm($request->validated());

        return to_route('account.show')->with('message', 'Your details are saved.');
    }
}
