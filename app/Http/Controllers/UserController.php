<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailList\MailListFacade;
use App\Exports\OldUsersExport;
use App\Exports\PennoMemberExport;
use App\Exports\UsersExport;
use App\Http\Requests\UserRequest;
use App\Models\Rol;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

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

    private UserRepository $_userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;
    }

    //gives the user views
    public function index()
    {
        $users = $this->_userRepository->getCurrentUsers(array('id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member'), ['roles']);
        $roles = Rol::all();

        return view('beheer.user.index', compact('users', 'roles'));
    }

    //gives the old users view
    public function indexOldMembers()
    {
        $users = $this->_userRepository->getOldUsers(array('id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member'));

        return view('beheer.user.index_old_users', compact('users'));
    }

    //show create screen
    public function create()
    {
        $fields = ['title' => 'Add user',
            'method' => 'POST',
            'url' => route('users.store')];
        $user = null;
        $roles = Rol::all();
        $ownedRoles = collect();
        return view('beheer.user.create_edit', compact('fields', 'user', 'roles', 'ownedRoles'));
    }

    //store user
    public function store(UserRequest $request)
    {
        $user = $this->_userRepository->create($request->all());
        if ($request->get('roles') != null) {
            $this->_userRepository->addRols($user->id, $request->get('roles'));
        }

        Session::flash("message", 'User is added');

        return to_route('users.index');
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return view('beheer.user.show', compact('user'));
    }

    //show edit screen
    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        $fields = ['title' => 'Edit user',
            'method' => 'PATCH',
            'url' => route('users.update', $user)];

        $roles = Rol::all();
        $ownedRoles = $user->roles;
        return view('beheer.user.create_edit', compact('fields', 'user', 'roles', 'ownedRoles'));
    }

    //update user
    public function update(UserRequest $request, User $user, MailListFacade $mailListFacade)
    {
        if ($user->email != $request['email']) {
            $mailListFacade->updateUserEmailForAllMailList($user, $user->email, $request['email']);
        }
        
        $this->_userRepository->update($user->id, $request->all());
        
        if (Auth::user()->can('manage-members')) {
            $this->_userRepository->addRols($user->id, $request->get('roles', []));
        }

        if (Auth::user()->id === $user->id) {
            return to_route('users.show', [$user, 'back' => 'false']);
        } else {
            Session::flash("message", 'Changes to user have been saved');

            return to_route('users.index');
        }
    }

    public function removeAsActiveMember(Request $request, User $user)
    {
        $user->removeAsActiveMember();

        return to_route('users.show', $user);
    }

    public function exportUsers(UsersExport $usersExport)
    {
        return $usersExport->download('Members.xlsx');
    }
    public function pennoMemberExportSelect(Request $request)
    {
        return view('beheer.user.penno_member_export');
    }
    public function pennoMemberExport(Request $request, UserRepository $userRepository)
    {
        $request->validate([
            'days_ago' => 'required|integer|min:0'
        ]);
        $export = new PennoMemberExport($userRepository, $request->integer('days_ago'));
        $exportDate = $export->getFromDate()->toDateString();
        return $export->download("Members (created after $exportDate).xlsx");
    }

    public function exportOldUsers(OldUsersExport $oldUsersExport)
    {
        return $oldUsersExport->download('Old members.xlsx');
    }

    public function makeActiveMember(Request $request, User $user)
    {
        if ($user->makeActiveMember()) //this should never fail unless some validation fails somewhere or the intended flow of the website breaks
        {
            return to_route('users.show', $user);
        }
        else
        {
            Session::flash("error", 'Failed to make member active');
            return to_route('users.index-old-members');
        }
    }
}
