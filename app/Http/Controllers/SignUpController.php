<?php

namespace App\Http\Controllers;

use App\Enums\PageSlug;
use App\Http\Requests\SignUpRequest;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * The public membership sign-up, which makes a pending member.
 */
class SignUpController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('throttle:5,1', only: ['store']),
        ];
    }

    public function create(): View
    {
        return view('site.signup', [
            'pageTitle' => 'Become a member',
            'content' => MenuItem::query()->where('slug', PageSlug::SignUp)->value('content'),
        ]);
    }

    public function store(SignUpRequest $request): RedirectResponse
    {
        User::signUp($request->safe()->except('g-recaptcha-response', 'privacy_policy', 'terms_and_conditions'));

        return to_route('signup.create')
            ->with('message', 'Your membership request is pending, we will get back to you as soon as possible');
    }
}
