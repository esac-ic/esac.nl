<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailList\MailListFacade;
use App\Exports\OldUsersExport;
use App\Exports\UsersExport;
use App\Repositories\UserRepository;
use App\Rol;
use App\Rules\EmailDomainValidator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

    private $_userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        // The edit, update, and show methods check the authorization themselves, so we don't apply a role middleware there.
        // Only the index method is accessible by both Administrators and Certificate admins, so we apply a different middleware there.
        $this->middleware('authorize:' . Config::get('constants.Administrator'))->except(['edit', 'update', 'show', 'index']);
        $this->middleware('authorize:' . Config::get('constants.Administrator') . ',' . Config::get('constants.Certificate_administrator'))->only(['index']);

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
            'url' => '/users'];
        $user = null;
        $roles = Rol::all();
        $ownedRoles = collect();
        return view('beheer.user.create_edit', compact('fields', 'user', 'roles', 'ownedRoles'));
    }

    //store user
    public function store(Request $request)
    {
        $this->validateInput($request);

        $user = $this->_userRepository->create($request->all());
        if ($request->get('roles') != null) {
            $this->_userRepository->addRols($user->id, $request->get('roles'));
        }

        Session::flash("message", 'User is added');

        return redirect('/users');
    }

    public function show(Request $request, User $user)
    {
        if (Auth::user()->id != $user->id && !Auth::user()->hasRole(Config::get('constants.Administrator'), Config::get('constants.Certificate_administrator'))) {
            abort(403, 'You do not have sufficient access to view this page');
        }
        return view('beheer.user.show', compact('user'));
    }

    //show edit screen
    public function edit(Request $request, User $user)
    {
        if (Auth::user()->id != $user->id && !Auth::user()->hasRole(Config::get('constants.Administrator'))) {
            abort(403, 'You do not have sufficient access to view this page');
        }

        $fields = ['title' => 'Edit user',
            'method' => 'PATCH',
            'url' => '/users/' . $user->id];

        $roles = Rol::all();
        $ownedRoles = $user->roles;
        return view('beheer.user.create_edit', compact('fields', 'user', 'roles', 'ownedRoles'));
    }

    //update user
    public function update(Request $request, User $user, MailListFacade $mailListFacade)
    {
        if (!Auth::user()->hasRole(Config::get('constants.Administrator'))) {
            if (Auth::user()->id != $user->id) {
                abort(403, 'You do not have sufficient access to view this page');
            }

            // If not an admin, set the fields that are not allowed to be changed to the current values.
            $request = $request->merge([
                'firstname' => $user->firstname,
                'preposition' => $user->preposition,
                'lastname' => $user->lastname,
                'remarks' => $user->remarks,
                'kind_of_member' => $user->kind_of_member,
            ]);
        }

        $this->validateInput($request, $user->id);

        if ($user->email != $request['email']) {
            $mailListFacade->updateUserEmailFormAllMailList($user, $user->email, $request['email']);
        }

        $this->_userRepository->update($user->id, $request->all());
        if (Auth::user()->hasRole(Config::get('constants.Administrator'))) {
            $this->_userRepository->addRols($user->id, $request->get('roles', []));
        }

        if (Auth::user()->id === $user->id) {
            return redirect('/users/' . $user->id . '?back=false');
        } else {
            Session::flash("message", 'Changes to user have been saved');

            return redirect('/users');
        }
    }

    public function removeAsActiveMember(Request $request, User $user, MailListFacade $mailListFacade)
    {
        $user->removeAsActiveMember();
        $mailListFacade->deleteUserFormAllMailList($user);

        return redirect('/users/' . $user->id);
    }

    public function exportUsers(UsersExport $usersExport)
    {
        return Excel::download($usersExport, 'Members' . '.xlsx');
    }

    public function exportOldUsers(OldUsersExport $oldUsersExport)
    {
        return Excel::download($oldUsersExport, 'Old members' . '.xlsx');
    }

    public function makeActiveMember(Request $request, User $user)
    {
        $user->makeActiveMember();

        return redirect('/users/' . $user->id);
    }

    private function validateInput(Request $request, int $userId = null)
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $userId, // Exclude the user being edited's email
                new EmailDomainValidator(),
            ],
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'street' => 'required|max:255',
            'houseNumber' => 'required|max:255',
            'city' => 'required|max:255',
            'zipcode' => 'required|max:255',
            'country' => 'required|max:255',
            'phonenumber' => 'required|max:255',
            'emergencyNumber' => 'required|max:255',
            'emergencyHouseNumber' => 'required|max:255',
            'emergencystreet' => 'required|max:255',
            'emergencycity' => 'required|max:255',
            'emergencyzipcode' => 'required|max:255',
            'emergencycountry' => 'required|max:255',
            'birthDay' => 'required|date',
            'IBAN' => 'required|max:255',
        ]);

        // These fields are only required for administrators.
        if (Auth::user()->hasRole(Config::get('constants.Administrator'))) {
            $this->validate($request, [
                'kind_of_member' => 'required',
            ]);
        }
    }
}
