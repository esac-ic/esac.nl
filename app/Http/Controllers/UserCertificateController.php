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

    public function create(User $user): View
    {
        return view('admin.users.certificate', [
            'user' => $user,
            'certificate' => null,
            'certificates' => Certificate::query()->orderBy('name')->whereKeyNot($user->certificates->modelKeys())->pluck('name', 'id')->all(),
        ]);
    }

    public function store(UserCertificateRequest $request, User $user): RedirectResponse
    {
        $user->certificates()->attach($request->input('certificate_id'));

        return to_route('admin.users.show', $user);
    }

    public function edit(User $user, Certificate $certificate): View
    {
        return view('admin.users.certificate', [
            'user' => $user,
            'certificate' => $certificate,
            'certificates' => Certificate::query()->orderBy('name')->pluck('name', 'id')->all(),
        ]);
    }

    public function update(UserCertificateRequest $request, User $user, Certificate $certificate): RedirectResponse
    {
        $user->certificates()->detach($certificate->id);
        $user->certificates()->attach($request->input('certificate_id'));

        return to_route('admin.users.show', $user);
    }

    public function destroy(User $user, Certificate $certificate): RedirectResponse
    {
        $user->certificates()->detach($certificate->id);

        return to_route('admin.users.show', $user);
    }
}
