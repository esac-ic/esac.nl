<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class StorePendingUserRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function rules(): array
    {
        return [
            ...$this->memberDetailRules(),
            'g-recaptcha-response' => ['required', new Recaptcha()],
            'incasso' => 'required',
            'privacy_policy' => 'required',
            'termsconditions' => 'required',
        ];
    }
}
