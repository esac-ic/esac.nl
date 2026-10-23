<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * A member updating their own details; their name, remark and member type are left to the administrators.
 */
class AccountRequest extends FormRequest
{
    use ValidatesMemberDetails;

    /**
     * An unchecked checkbox isn't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['direct_debit' => $this->boolean('direct_debit')]);
    }

    public function rules(): array
    {
        return [
            ...Arr::except($this->memberDetailRules($this->user()->id), ['first_name', 'preposition', 'last_name']),
            // Leaving the password empty keeps the current one.
            'password' => [Rule::excludeIf($this->isNotFilled('password')), 'required', 'confirmed', Password::defaults()],
            'direct_debit' => ['boolean'],
        ];
    }
}
