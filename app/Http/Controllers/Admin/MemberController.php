<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberType;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class MemberController extends Controller implements HasMiddleware
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
        return view('admin.members.index', [
            'users' => User::query()->active()->with('roles')->get(['id', 'first_name', 'last_name', 'email', 'preposition', 'member_type']),
            'roles' => Role::all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.members.form', ['user' => new User, 'roles' => Role::all(), 'memberTypes' => MemberType::options()]);
    }

    public function store(MemberRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validated());
        $user->roles()->sync($request->input('roles', []));

        return to_route('admin.members.index')->with('message', 'Member added');
    }

    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('admin.members.show', ['user' => $user]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('admin.members.form', ['user' => $user, 'roles' => Role::all(), 'memberTypes' => MemberType::options()]);
    }

    public function update(MemberRequest $request, User $user): RedirectResponse
    {
        $user->updateFromForm($request->validated());

        if ($request->user()->can('manage-members')) {
            $user->roles()->sync($request->input('roles', []));
        }

        if ($request->user()->is($user)) {
            return to_route('admin.members.show', $user);
        }

        return to_route('admin.members.index')->with('message', 'Member saved');
    }
}
