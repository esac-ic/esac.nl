<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Exports\UsersExport;
use App\CustomClasses\MailList\MailListFacade;
use App\Repositories\RepositorieFactory as RepositorieFactory;
use App\Rol;
use App\Rules\EmailDomainValidator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
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
    public function __construct(RepositorieFactory $repositoryFactory)
    {
        $this->middleware('auth');
        // The edit, update, and show methods check the authorization themselves, so we don't apply a role middleware there.
        // Only the index method is accessible by both Administrators and Certificate admins, so we apply a different middleware there.
        $this->middleware('authorize:'.\Config::get('constants.Administrator'))->except(['edit', 'update', 'show', 'index']);
        $this->middleware('authorize:'.\Config::get('constants.Administrator') .',' . \Config::get('constants.Certificate_administrator'))->only(['index']);
        $this->_userRepository = $repositoryFactory->getRepositorie(RepositorieFactory::$USERREPOKEY);
    }

    //gives the user views
    public function index(){
        $users = $this->_userRepository->getCurrentUsers(array('id','firstname','lastname','email','preposition','kind_of_member'), ['roles']);
        $roles = Rol::all();

        return view('beheer.user.index', compact('users','roles'));
    }

    //gives the old users view
    public function indexOldMembers() {
        $users = $this->_userRepository->getOldUsers(array('id','firstname','lastname','email','preposition','kind_of_member'));

        return view('beheer.user.index_old_users', compact('users'));
    }

    //show create screen
    public function create(){
        $fields = ['title' => trans('user.add'),
            'method' => 'POST',
            'url' => '/users',];
        $user = null;
        $roles = Rol::all();
        $ownedRoles = collect();
        return view('beheer.user.create_edit', compact('fields','user','roles', 'ownedRoles'));
    }

    //store user
    public function store(Request $request){
        $this->validateInput($request);

        $user = $this->_userRepository->create($request->all());
        if(Input::get('roles') != null){
            $this->_userRepository->addRols($user->id,Input::get('roles'));
        }

        Session::flash("message", trans('user.added'));

        return redirect('/users');
    }

    public function show(Request $request, User $user){
        if(Auth::user()->id != $user->id && !Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator'))){
            abort(403, trans('validation.Unauthorized'));
        }
        return view('beheer.user.show', compact('user'));
    }

    //show edit screen
    public function edit(Request $request,User $user){
        if(Auth::user()->id != $user->id && !Auth::user()->hasRole(Config::get('constants.Administrator'))){
            abort(403, trans('validation.Unauthorized'));
        }

        $fields = ['title' => trans('user.edit'),
            'method' => 'PATCH',
            'url' => '/users/'. $user->id];

        $roles = Rol::all();
        $ownedRoles = $user->roles;
        return view('beheer.user.create_edit', compact('fields','user','roles', 'ownedRoles'));
    }

    //update user
    public function update(Request $request, User $user, MailListFacade $mailListFacade){
        if(!Auth::user()->hasRole(Config::get('constants.Administrator'))){
            if(Auth::user()->id != $user->id || $request->has('kind_of_member')){
                abort(403, trans('validation.Unauthorized'));
            }
        }
        if($user->email != $request['email']){
            //check if email is unique
            $this->validateInput($request);
            $mailListFacade->updateUserEmailFormAllMailList($user,$user->email,$request['email']);
        }

        $this->_userRepository->update($user->id, $request->all());
        if(Auth::user()->hasRole(Config::get('constants.Administrator'))){
            $this->_userRepository->addRols($user->id,Input::get('roles',[]));
        }

        if(Auth::user()->id === $user->id){
            return redirect('/users/'. $user->id . '?back=false');
        } else{
            Session::flash("message", trans('user.edited'));

            return redirect('/users');
        }
    }

    public function removeAsActiveMember(Request $request, User $user, MailListFacade $mailListFacade){
        $user->removeAsActiveMember();
        $mailListFacade->deleteUserFormAllMailList($user);

        return redirect('/users/'. $user->id);
    }

    public function exportUsers(UsersExport $usersExport){
        return Excel::download($usersExport, trans('user.members') . '.xlsx');
    }

    public function makeActiveMember(Request $request, User $user){
        $user->makeActiveMember();

        return redirect('/users/'. $user->id);
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users',
                new EmailDomainValidator()
            ],
            'firstname' => 'required',
            'lastname' => 'required',
            'street' => 'required',
            'houseNumber' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'country' => 'required',
            'phonenumber' => 'required',
            'emergencyNumber' => 'required',
            'emergencyHouseNumber' => 'required',
            'emergencystreet' => 'required',
            'emergencycity' => 'required',
            'emergencyzipcode' => 'required',
            'emergencycountry' => 'required',
            'birthDay' => 'required|date',
            'gender' => 'required',
            'IBAN' => 'required'
        ]);

        // These fields are only required for administrators.
        if (Auth::user()->hasRole(Config::get('constants.Administrator'))) {
            $this->validate($request, [
                'kind_of_member' => 'required',
            ]);
        }
    }
}
