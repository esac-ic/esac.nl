<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * A member updating their own details; their name, remark and member type are left to the administrators,
 * and their email address and password are changed through Fortify.
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
            ...Arr::except($this->memberDetailRules($this->user()->id), ['email', 'first_name', 'preposition', 'last_name']),
            'direct_debit' => ['boolean'],
        ];
    }
}
