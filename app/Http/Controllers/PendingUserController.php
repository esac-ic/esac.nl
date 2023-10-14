<?php

namespace App\Http\Controllers;

use App\Exports\UserRegistrationInfoExport;
use App\Repositories\UserRepository;
use App\Rules\EmailDomainValidator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class PendingUserController extends Controller
{

    private $_userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth')->except('storePendingUser');
        $this->middleware('authorize:' . Config::get('constants.Administrator'))->except('storePendingUser');

        $this->_userRepository = $userRepository;
    }

    //pending users view
    public function indexPendingMembers()
    {
        $users = $this->_userRepository->getPendingUsers(array('id', 'firstname', 'lastname', 'email', 'preposition', 'kind_of_member'));

        return view('beheer.user.index_pending_users', compact('users'));
    }

    //store pending user
    public function storePendingUser(Request $request)
    {
        $this->validateInput($request);

        $user = $this->_userRepository->createPendingUser($request->all());

        Session::flash("message", 'Your membership request is pending, we will get back to you as soon as possible');

        return redirect('/signup');
    }

    public function removeAsPendingMember(Request $request, User $user)
    {
        $registration_info = $user->registrationInfo();
        if (!is_null($registration_info)) {
            $registration_info->delete();
        }

        $user->removeAsPendingMember();

        return redirect('users/pending_members');
    }

    public function approveAsPendingMember(Request $request, User $user)
    {
        $user->approveAsPendingMember();

        return redirect('users/pending_members');
    }

    public function getRegistrationExportData()
    {
        return Excel::download(
            new UserRegistrationInfoExport(),
            'Registration info' . '.xlsx'
        );
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users',
                new EmailDomainValidator(),
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
            'IBAN' => 'required',
            'g-recaptcha-response' => 'required',
            'incasso' => 'required',
            'privacy_policy' => 'required',
            'termsconditions' => 'required',
        ]);
    }
}
