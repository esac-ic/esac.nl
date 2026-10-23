<?php

namespace App\Http\Requests;

use App\Enums\MemberType;
use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * An administrator creating or updating a member.
 */
class MemberRequest extends FormRequest
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
            ...$this->memberDetailRules($this->route('user')?->id),
            // Leaving the password empty on an update keeps the current one.
            'password' => [
                Rule::excludeIf($this->route('user') !== null && $this->isNotFilled('password')),
                'required',
                'confirmed',
                Password::defaults(),
            ],
            'direct_debit' => ['boolean'],
            'remark' => ['nullable'],
            'member_type' => ['required', Rule::enum(MemberType::class)],
        ];
    }
}
