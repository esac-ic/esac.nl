<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCertificateRequest;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserCertificateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-certificates'),
        ];
    }

    public function addCertificate(User $user): View
    {
        return view('beheer.user.addCertificate', [
            'fields' => [
                'title' => 'Add certificate',
                'method' => 'POST',
                'url' => route('users.certificates.store', $user),
            ],
            'certificates' => Certificate::query()->whereKeyNot($user->certificates->modelKeys())->pluck('name', 'id')->all(),
            'userCertificate' => null,
        ]);
    }

    public function saveCertificate(UserCertificateRequest $request, User $user): RedirectResponse
    {
        $user->certificates()->attach($request->input('certificate_id'));

        return to_route('users.show', $user);
    }

    public function editUserCertificate(User $user, Certificate $certificate): View
    {
        return view('beheer.user.addCertificate', [
            'fields' => [
                'title' => 'Edit certificate',
                'method' => 'PATCH',
                'url' => route('users.certificates.update', [$user, $certificate]),
            ],
            'certificates' => Certificate::query()->pluck('name', 'id')->all(),
            'userCertificate' => $user->certificates()->find($certificate->id),
        ]);
    }

    public function updateUserCertificate(UserCertificateRequest $request, User $user, Certificate $certificate): RedirectResponse
    {
        $user->certificates()->detach($certificate->id);
        $user->certificates()->attach($request->input('certificate_id'));

        return to_route('users.show', $user);
    }

    public function deleteUserCertificate(User $user, Certificate $certificate): RedirectResponse
    {
        $user->certificates()->detach($certificate->id);

        return to_route('users.show', $user);
    }
}
