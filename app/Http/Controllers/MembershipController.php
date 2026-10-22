<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

/**
 * Makes an old member active again (store) or a member an old member (destroy).
 */
class MembershipController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-members'),
        ];
    }

    public function store(User $user): RedirectResponse
    {
        // This only fails when the member is already active or has no email address.
        if ($user->makeActiveMember()) {
            return to_route('admin.users.show', $user);
        }

        return to_route('admin.old-members.index')->with('error', 'Failed to make member active');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->removeAsActiveMember();

        if ($request->user()->is($user)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('front-end.home');
        }

        return to_route('admin.users.show', $user);
    }
}
