<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Certificate;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserCertificateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('authorize:' . Role::Administrator->value . ',' . Role::CertificateAdministrator->value),
        ];
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly UserRepository $_userRepository)
    {
    }

    public function addCertificate(Request $request, User $user): View
    {
        $fields = ['title' => 'Add certificate',
            'method' => 'POST',
            'url' => route('users.certificates.store', $user)];

        $certificates = [];

        $userCertificates = $this->_userRepository->getUserCertificates($user->id);

        foreach (Certificate::all() as $cetificate) {
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

        return to_route('users.show', $user);
    }

    public function editUserCertificate(Request $request, User $user, Certificate $certificate): View
    {
        $fields = ['title' => 'Edit certificate',
            'method' => 'PATCH',
            'url' => route('users.certificates.update', [$user, $certificate])];

        $certificates = [];

        foreach (Certificate::all() as $cetificate) {
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
        return to_route('users.show', $user);
    }

    public function deleteUserCertificate(Request $request, User $user, Certificate $certificate): RedirectResponse
    {
        $user->certificates()->detach($certificate->id);

        return to_route('users.show', $user);
    }

    private function validateInput(Request $request): void
    {
        $request->validate([
            'certificate_id' => 'required|numeric|min:0',
        ]);
    }
}
