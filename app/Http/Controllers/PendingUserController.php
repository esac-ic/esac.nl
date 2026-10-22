<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendingUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class PendingUserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['storePendingUser']),
            new Middleware('can:manage-members', except: ['storePendingUser']),
        ];
    }

    //pending users view
    public function indexPendingMembers()
    {
        $users = User::pending()->get(['id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member']);

        return view('beheer.user.index_pending_users', compact('users'));
    }

    //store pending user
    public function storePendingUser(StorePendingUserRequest $request)
    {
        User::createPending($request->all());
        
        Session::flash("message", 'Your membership request is pending, we will get back to you as soon as possible');

        return to_route('front-end.signup');
    }

    public function removeAsPendingMember(Request $request, User $user)
    {
        $user->removeAsPendingMember();

        return to_route('users.indexPendingMembers');
    }

    public function approveAsPendingMember(Request $request, User $user)
    {
        $user->approveAsPendingMember();
        
        return to_route('users.indexPendingMembers');
    }
}
