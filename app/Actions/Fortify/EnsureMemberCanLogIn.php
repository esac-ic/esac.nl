<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

/**
 * Old and pending members can't log in. They only get told so after entering their correct password.
 */
class EnsureMemberCanLogIn
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = User::query()->where(Fortify::username(), $request->input(Fortify::username()))->first();

        if ($user?->password !== null && Hash::check($request->input('password'), $user->password)) {
            if ($user->isOldMember()) {
                throw ValidationException::withMessages([Fortify::username() => 'Old members can not login']);
            }

            if ($user->isPendingMember()) {
                throw ValidationException::withMessages([Fortify::username() => 'Pending members can not login']);
            }
        }

        return $next($request);
    }
}
