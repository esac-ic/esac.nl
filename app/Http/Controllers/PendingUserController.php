<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendingUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PendingUserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['storePendingUser']),
            new Middleware('can:manage-members', except: ['storePendingUser']),
        ];
    }

    public function indexPendingMembers(): View
    {
        return view('beheer.user.index_pending_users', [
            'users' => User::pending()->get(['id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member']),
        ]);
    }

    public function storePendingUser(StorePendingUserRequest $request): RedirectResponse
    {
        User::createPending($request->safe()->except('g-recaptcha-response', 'privacy_policy', 'termsconditions'));

        return to_route('front-end.signup')
            ->with('message', 'Your membership request is pending, we will get back to you as soon as possible');
    }

    public function removeAsPendingMember(User $user): RedirectResponse
    {
        $user->removeAsPendingMember();

        return to_route('users.indexPendingMembers');
    }

    public function approveAsPendingMember(User $user): RedirectResponse
    {
        $user->approveAsPendingMember();

        return to_route('users.indexPendingMembers');
    }
}
