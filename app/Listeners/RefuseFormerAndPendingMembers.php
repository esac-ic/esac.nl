<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

/**
 * Former and pending members can't log in. Laravel has checked the password by now, so only they learn why.
 */
class RefuseFormerAndPendingMembers
{
    public function handle(Validated $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if ($user->isFormerMember()) {
            throw ValidationException::withMessages([Fortify::username() => "Former members can't log in"]);
        }

        if ($user->isPendingMember()) {
            throw ValidationException::withMessages([Fortify::username() => "Pending members can't log in"]);
        }
    }
}
