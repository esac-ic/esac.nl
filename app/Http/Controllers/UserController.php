<?php

namespace App\Http\Controllers;

use App\Exports\OldUsersExport;
use App\Exports\PennoMemberExport;
use App\Exports\UsersExport;
use App\Http\Requests\UserRequest;
use App\Models\Rol;
use App\Models\User;
use App\Services\Mailman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // The edit, update, and show methods check the authorization themselves, so they get no ability middleware.
        // The index is also open to those who can only view members.
        return [
            'auth',
            new Middleware('can:manage-members', except: ['edit', 'update', 'show', 'index']),
            new Middleware('can:view-members', only: ['index']),
        ];
    }

    public function index(): View
    {
        return view('beheer.user.index', [
            'users' => User::current()->with('roles')->get(['id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member']),
            'roles' => Rol::all(),
        ]);
    }

    public function indexOldMembers(): View
    {
        return view('beheer.user.index_old_users', [
            'users' => User::old()->get(['id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member']),
        ]);
    }

    public function create(): View
    {
        return view('beheer.user.create_edit', [
            'fields' => [
                'title' => 'Add user',
                'method' => 'POST',
                'url' => route('users.store'),
            ],
            'user' => null,
            'roles' => Rol::all(),
            'ownedRoles' => collect(),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::fromForm($request->all());
        $user->save();
        if ($request->get('roles') != null) {
            $user->roles()->sync($request->get('roles'));
        }

        return to_route('users.index')->with('message', 'User is added');
    }

    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        return view('beheer.user.show', ['user' => $user]);
    }

    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        return view('beheer.user.create_edit', [
            'fields' => [
                'title' => 'Edit user',
                'method' => 'PATCH',
                'url' => route('users.update', $user),
            ],
            'user' => $user,
            'roles' => Rol::all(),
            'ownedRoles' => $user->roles,
        ]);
    }

    public function update(UserRequest $request, User $user, Mailman $mailman): RedirectResponse
    {
        if ($user->email != $request['email']) {
            $mailman->updateUserEmailForAllMailList($user, $user->email, $request['email']);
        }

        $user->updateFromForm($request->all());

        if ($request->user()->can('manage-members')) {
            $user->roles()->sync($request->get('roles', []));
        }

        if ($request->user()->is($user)) {
            return to_route('users.show', [$user, 'back' => 'false']);
        }

        return to_route('users.index')->with('message', 'Changes to user have been saved');
    }

    public function removeAsActiveMember(User $user): RedirectResponse
    {
        $user->removeAsActiveMember();

        return to_route('users.show', $user);
    }

    public function exportUsers(UsersExport $usersExport): StreamedResponse
    {
        return $usersExport->download('Members.xlsx');
    }

    public function pennoMemberExportSelect(): View
    {
        return view('beheer.user.penno_member_export');
    }

    public function pennoMemberExport(Request $request): StreamedResponse
    {
        $request->validate([
            'days_ago' => 'required|integer|min:0',
        ]);
        $export = new PennoMemberExport($request->integer('days_ago'));
        $exportDate = $export->getFromDate()->toDateString();

        return $export->download("Members (created after $exportDate).xlsx");
    }

    public function exportOldUsers(OldUsersExport $oldUsersExport): StreamedResponse
    {
        return $oldUsersExport->download('Old members.xlsx');
    }

    public function makeActiveMember(User $user): RedirectResponse
    {
        // This only fails when the member is already active or has no email address.
        if ($user->makeActiveMember()) {
            return to_route('users.show', $user);
        }

        return to_route('users.index-old-members')->with('error', 'Failed to make member active');
    }
}
