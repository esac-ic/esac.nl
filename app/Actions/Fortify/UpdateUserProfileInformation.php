<?php

namespace App\Actions\Fortify;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

/**
 * Changes a member's email address; their other details are changed with AccountRequest.
 */
class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    use ValidatesMemberDetails;

    /**
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function update(User $user, array $input): void
    {
        $validated = Validator::make($input, [
            'email' => $this->memberDetailRules($user->id)['email'],
            // So a session left open isn't enough to take over the account.
            'current_password' => ['required', 'string', 'current_password:web'],
        ])->validateWithBag('updateProfileInformation');

        // Through the model, so the mailing lists move to the new address.
        $user->updateFromForm(['email' => $validated['email']]);
    }
}
