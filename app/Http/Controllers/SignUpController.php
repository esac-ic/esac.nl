<?php

namespace App\Http\Controllers;

use App\Enums\PageSlug;
use App\Http\Requests\SignUpRequest;
use App\Models\Page;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * The public membership sign-up, which makes a pending member.
 */
class SignUpController extends Controller
{
    public function create(): View
    {
        return view('site.sign-up', [
            'pageTitle' => 'Become a member',
            'content' => Page::contentFor(PageSlug::SignUp),
        ]);
    }

    public function store(SignUpRequest $request): RedirectResponse
    {
        User::signUp($request->safe()->except('g-recaptcha-response', 'privacy_policy', 'terms_and_conditions'));

        return to_route('sign-up.create')
            ->with('message', 'Your membership request is pending, we will get back to you as soon as possible');
    }
}
