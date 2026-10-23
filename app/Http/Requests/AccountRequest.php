<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * A member updating their own details; their name, remark and member type are left to the administrators,
 * and their email address and password are changed with AccountLoginRequest.
 */
class AccountRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function rules(): array
    {
        return [
            ...Arr::except($this->memberDetailRules($this->user()->id), ['email', 'first_name', 'preposition', 'last_name']),
            'direct_debit' => ['boolean'],
        ];
    }
}
