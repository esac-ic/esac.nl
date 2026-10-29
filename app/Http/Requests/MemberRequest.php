<?php

namespace App\Http\Requests;

use App\Enums\MemberType;
use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Creating a user (administrators) or updating one (administrators, or members updating themselves).
 */
class MemberRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user === null || $this->user()->can('update', $user);
    }

    /**
     * An unchecked checkbox isn't posted. Members updating themselves can't change their name, remark or member type.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['direct_debit' => $this->boolean('direct_debit')]);

        $user = $this->route('user');

        if ($user instanceof User && ! $this->canManageMembers()) {
            $this->merge(Arr::only($user->attributesToArray(), ['first_name', 'preposition', 'last_name', 'remark', 'member_type']));
        }
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
            'member_type' => [Rule::requiredIf($this->canManageMembers()), Rule::enum(MemberType::class)],
        ];
    }

    private function canManageMembers(): bool
    {
        return $this->user()->can('manage-members');
    }
}
