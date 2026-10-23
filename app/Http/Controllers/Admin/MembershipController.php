<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

/**
 * Restores a former member's membership (store) or ends a member's membership (destroy).
 */
class MembershipController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage-members'),
        ];
    }

    public function store(User $user): RedirectResponse
    {
        $user->restoreMembership();

        return to_route('admin.members.show', $user);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->endMembership();

        if ($request->user()->is($user)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return to_route('home');
        }

        return to_route('admin.members.show', $user);
    }
}
