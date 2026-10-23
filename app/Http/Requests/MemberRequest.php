<?php

namespace App\Http\Requests;

use App\Enums\AdminRole;
use App\Enums\MemberType;
use App\Http\Requests\Concerns\ValidatesMemberDetails;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * An administrator creating or updating a member.
 */
class MemberRequest extends FormRequest
{
    use ValidatesMemberDetails;

    /**
     * Unchecked checkboxes aren't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['direct_debit' => $this->boolean('direct_debit'), 'roles' => $this->input('roles', [])]);
    }

    public function rules(): array
    {
        return [
            ...$this->memberDetailRules($this->route('user')?->id),
            // Leaving the password empty on an update keeps the current one.
            'password' => [
                Rule::excludeIf($this->route('user') !== null && $this->isNotFilled('password')),
                ...$this->passwordRules(),
            ],
            'direct_debit' => ['boolean'],
            'remark' => ['nullable'],
            'member_type' => ['required', Rule::enum(MemberType::class)],
            // Only those who manage the roles can hand them out.
            'roles' => [Rule::excludeIf($this->user()->cannot('manage-roles')), 'array'],
            'roles.*' => [Rule::enum(AdminRole::class)],
        ];
    }
}
