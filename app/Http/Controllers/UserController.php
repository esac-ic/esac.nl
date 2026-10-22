<?php

namespace App\Http\Controllers;

use App\Enums\KindOfMember;
use App\Http\Requests\UserRequest;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // The edit, update, and show methods check the authorization themselves, so they get no ability middleware.
        // The index is also open to those who can only view members.
        return [
            'auth',
            new Middleware('can:manage-members', only: ['create', 'store']),
            new Middleware('can:view-members', only: ['index']),
        ];
    }

    public function index(): View
    {
        return view('beheer.user.index', [
            'users' => User::query()->current()->with('roles')->get(['id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member']),
            'roles' => Rol::all(),
        ]);
    }

    public function create(): View
    {
        return view('beheer.user.create_edit', ['user' => new User, 'roles' => Rol::all(), 'kindsOfMember' => KindOfMember::options()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::fromForm($request->validated());
        $user->save();
        $user->roles()->sync($request->input('roles', []));

        return to_route('beheer.users.index')->with('message', 'User is added');
    }

    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('beheer.user.show', ['user' => $user]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('beheer.user.create_edit', ['user' => $user, 'roles' => Rol::all(), 'kindsOfMember' => KindOfMember::options()]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->updateFromForm($request->validated());

        if ($request->user()->can('manage-members')) {
            $user->roles()->sync($request->input('roles', []));
        }

        if ($request->user()->is($user)) {
            return to_route('beheer.users.show', [$user, 'back' => 'false']);
        }

        return to_route('beheer.users.index')->with('message', 'Changes to user have been saved');
    }
}
