<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Repositories\CertificateRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserCertificateController extends Controller
{
    private $_userRepository;
    private $_certificateRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, CertificateRepository $certificateRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator') . ',' . Config::get('constants.Certificate_administrator'));

        $this->_userRepository = $userRepository;
        $this->_certificateRepository = $certificateRepository;
    }

    public function addCertificate(Request $request, User $user)
    {
        $fields = ['title' => 'Add certificate',
            'method' => 'POST',
            'url' => '/users/' . $user->id . '/addCertificate'];

        $certificates = array();

        $userCertificates = $this->_userRepository->getUserCertificates($user->id);

        foreach ($this->_certificateRepository->all() as $cetificate) {
            if (!$userCertificates->contains($cetificate)) {
                $certificates[$cetificate->id] = $cetificate->name;
            }
        }

        $userCertificate = null;

        return view('beheer.user.addCertificate', compact(["fields", "certificates", "userCertificate"]));
    }

    public function saveCertificate(Request $request, User $user)
    {
        $this->validateInput($request);
        $this->_userRepository->addCertificate($user->id, $request->all());

        return redirect('/users/' . $user->id);
    }

    public function editUserCertificate(Request $request, User $user, Certificate $certificate)
    {
        $fields = ['title' => 'Edit certificate',
            'method' => 'PATCH',
            'url' => '/users/' . $user->id . '/addCertificate/' . $certificate->id];

        $certificates = array();

        foreach ($this->_certificateRepository->all() as $cetificate) {
            $certificates[$cetificate->id] = $cetificate->name;
        }

        $userCertificate = $user->certificates()->find($certificate->id);

        return view('beheer.user.addCertificate', compact(["fields", "certificates", "userCertificate"]));
    }

    public function updateUserCertificate(Request $request, User $user, Certificate $certificate)
    {
        $this->validateInput($request);
        $user->certificates()->detach($certificate->id);
        $this->_userRepository->addCertificate($user->id, $request->all());
        return redirect('/users/' . $user->id);
    }

    public function deleteUserCertificate(Request $request, User $user, Certificate $certificate)
    {
        $user->certificates()->detach($certificate->id);

        return redirect('/users/' . $user->id);
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            'certificate_id' => 'required|numeric|min:0',
        ]);
    }
}
