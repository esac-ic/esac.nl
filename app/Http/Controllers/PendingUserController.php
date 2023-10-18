<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Rules\EmailDomainValidator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

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
        $user->removeAsPendingMember();

        return redirect('users/pending_members');
    }

    public function approveAsPendingMember(Request $request, User $user)
    {
        $user->approveAsPendingMember();

        return redirect('users/pending_members');
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
            'g-recaptcha-response' => 'required',
            'incasso' => 'required',
            'privacy_policy' => 'required',
            'termsconditions' => 'required',
        ]);
    }
}
