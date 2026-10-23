<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * A member updating their own details; their name, remark and member type are left to the administrators.
 */
class AccountRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function rules(): array
    {
        return [
            ...Arr::except($this->memberDetailRules($this->user()->id), ['first_name', 'preposition', 'last_name']),
            // Leaving the password empty keeps the current one.
            'password' => [Rule::excludeIf($this->isNotFilled('password')), ...$this->passwordRules()],
            'direct_debit' => ['boolean'],
            // A new email address or password takes the current password, so a session left open isn't enough to take over the account.
            'current_password' => [
                Rule::excludeIf($this->isNotFilled('password') && $this->input('email') === $this->user()->email),
                'required',
                'current_password',
            ],
        ];
    }
}
