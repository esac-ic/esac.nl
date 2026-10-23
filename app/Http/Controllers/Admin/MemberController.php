<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MemberType;
use App\Exports\MembersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // Those who can only view members see the list and a member's certificates and registrations.
        return [
            new Middleware('can:view-members', only: ['index', 'show']),
            new Middleware('can:manage-members', except: ['index', 'show']),
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
        return view('admin.members.show', ['user' => $user->load('registrations.event')]);
    }

    public function edit(User $user): View
    {
        return view('admin.members.form', ['user' => $user, 'roles' => Role::all(), 'memberTypes' => MemberType::options()]);
    }

    public function update(MemberRequest $request, User $user): RedirectResponse
    {
        $user->updateFromForm($request->validated());
        $user->roles()->sync($request->input('roles', []));

        return to_route('admin.members.index')->with('message', 'Member saved');
    }

    public function export(MembersExport $membersExport): StreamedResponse
    {
        return $membersExport->download('Members.xlsx');
    }
}
