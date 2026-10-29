<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Approves or rejects the sign-ups of pending members.
 */
class PendingMemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-members'),
        ];
    }

    public function index(): View
    {
        return view('admin.pending-members.index', [
            'users' => User::query()->pending()->get(['id', 'first_name', 'last_name', 'email', 'preposition', 'member_type']),
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->rejectSignUp();

        return to_route('admin.pending-members.index');
    }

    public function approve(User $user): RedirectResponse
    {
        $user->approveSignUp();

        return to_route('admin.pending-members.index');
    }
}
