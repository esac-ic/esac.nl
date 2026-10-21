<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use App\Repositories\CertificateRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserCertificateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly UserRepository $_userRepository, private readonly CertificateRepository $_certificateRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator') . ',' . Config::get('constants.Certificate_administrator'));
    }

    public function addCertificate(Request $request, User $user): View
    {
        $fields = ['title' => 'Add certificate',
            'method' => 'POST',
            'url' => '/users/' . $user->id . '/addCertificate'];

        $certificates = [];

        $userCertificates = $this->_userRepository->getUserCertificates($user->id);

        foreach ($this->_certificateRepository->all() as $cetificate) {
            if (!$userCertificates->contains($cetificate)) {
                $certificates[$cetificate->id] = $cetificate->name;
            }
        }

        $userCertificate = null;

        return view('beheer.user.addCertificate', compact(["fields", "certificates", "userCertificate"]));
    }

    public function saveCertificate(Request $request, User $user): RedirectResponse
    {
        $this->validateInput($request);
        $this->_userRepository->addCertificate($user->id, $request->all());

        return redirect('/users/' . $user->id);
    }

    public function editUserCertificate(Request $request, User $user, Certificate $certificate): View
    {
        $fields = ['title' => 'Edit certificate',
            'method' => 'PATCH',
            'url' => '/users/' . $user->id . '/addCertificate/' . $certificate->id];

        $certificates = [];

        foreach ($this->_certificateRepository->all() as $cetificate) {
            $certificates[$cetificate->id] = $cetificate->name;
        }

        $userCertificate = $user->certificates()->find($certificate->id);

        return view('beheer.user.addCertificate', compact(["fields", "certificates", "userCertificate"]));
    }

    public function updateUserCertificate(Request $request, User $user, Certificate $certificate): RedirectResponse
    {
        $this->validateInput($request);
        $user->certificates()->detach($certificate->id);
        $this->_userRepository->addCertificate($user->id, $request->all());
        return redirect('/users/' . $user->id);
    }

    public function deleteUserCertificate(Request $request, User $user, Certificate $certificate): RedirectResponse
    {
        $user->certificates()->detach($certificate->id);

        return redirect('/users/' . $user->id);
    }

    private function validateInput(Request $request): void
    {
        $this->validate($request, [
            'certificate_id' => 'required|numeric|min:0',
        ]);
    }
}
