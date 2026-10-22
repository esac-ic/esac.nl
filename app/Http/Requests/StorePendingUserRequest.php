<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StorePendingUserRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function rules(): array
    {
        return [
            ...$this->memberDetailRules(),
            'password' => ['required', 'confirmed', Password::defaults()],
            'g-recaptcha-response' => ['required', new Recaptcha],
            'incasso' => ['accepted'],
            'privacy_policy' => ['required'],
            'termsconditions' => ['required'],
        ];
    }

    /**
     * An unchecked checkbox isn't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['incasso' => $this->boolean('incasso')]);
    }
}
