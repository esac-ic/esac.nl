<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AccountLoginRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function rules(): array
    {
        return [
            ...Arr::only($this->memberDetailRules($this->user()->id), ['email']),
            // Leaving the password empty keeps the current one.
            'password' => [Rule::excludeIf($this->isNotFilled('password')), ...$this->passwordRules()],
            // So a session left open isn't enough to take over the account.
            'current_password' => ['required', 'current_password'],
        ];
    }
}
