<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function rules(): array
    {
        return [
            ...$this->memberDetailRules(),
            'password' => $this->passwordRules(),
            'g-recaptcha-response' => ['required', new Recaptcha, 'exclude'],
            'direct_debit' => ['accepted'],
            'privacy_policy' => ['accepted', 'exclude'],
            'terms_and_conditions' => ['accepted', 'exclude'],
        ];
    }
}
