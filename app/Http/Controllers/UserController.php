<?php

namespace App\Http\Controllers;

use App\CustomClasses\MailList\MailListFacade;
use App\Enums\Role;
use App\Exports\OldUsersExport;
use App\Exports\PennoMemberExport;
use App\Exports\UsersExport;
use App\Models\Rol;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Rules\EmailDomainValidator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        // The edit, update, and show methods check the authorization themselves, so they get no role middleware.
        // The index is open to both Administrators and Certificate administrators.
        return [
            'auth',
            new Middleware('authorize:' . Role::Administrator->value, except: ['edit', 'update', 'show', 'index']),
            new Middleware('authorize:' . Role::Administrator->value . ',' . Role::CertificateAdministrator->value, only: ['index']),
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
        if (Auth::user()->id != $user->id && !Auth::user()->hasRole(Role::Administrator->value, Role::CertificateAdministrator->value)) {
            abort(403, 'You do not have sufficient access to view this page');
        }
        return view('beheer.user.show', compact('user'));
    }

    //show edit screen
    public function edit(Request $request, User $user)
    {
        if (Auth::user()->id != $user->id && !Auth::user()->hasRole(Role::Administrator->value)) {
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
        if (!Auth::user()->hasRole(Role::Administrator->value)) {
            if (Auth::user()->id != $user->id) {
                abort(403, 'You do not have sufficient access to view this page');
            }

            // If not an admin, set the fields that are not allowed to be changed to the current values.
            $request = $request->merge([
                'firstname' => $user->firstname,
                'preposition' => $user->preposition,
                'lastname' => $user->lastname,
                'remark' => $user->remark,
                'kind_of_member' => $user->kind_of_member,
            ]);
        }
        
        $this->validateInput($request, $user->id);
        
        if ($user->email != $request['email']) {
            $mailListFacade->updateUserEmailForAllMailList($user, $user->email, $request['email']);
        }
        
        $this->_userRepository->update($user->id, $request->all());
        
        if (Auth::user()->hasRole(Role::Administrator->value)) {
            $this->_userRepository->addRols($user->id, $request->get('roles', []));
        }

        if (Auth::user()->id === $user->id) {
            return redirect('/users/' . $user->id . '?back=false');
        } else {
            Session::flash("message", 'Changes to user have been saved');

            return redirect('/users');
        }
    }

    public function removeAsActiveMember(Request $request, User $user)
    {
        $user->removeAsActiveMember();

        return redirect('/users/' . $user->id);
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
            return redirect('/users/' . $user->id);
        }
        else
        {
            Session::flash("error", 'Failed to make member active');
            return redirect(route('users.index-old-members'));
        }
    }

    private function validateInput(Request $request, ?int $userId = null)
    {
        $request->validate([
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
        if (Auth::user()->hasRole(Role::Administrator->value)) {
            $request->validate([
                'kind_of_member' => 'required',
            ]);
        }
    }
}
