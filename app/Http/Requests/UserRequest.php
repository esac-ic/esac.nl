<?php

namespace App\Http\Requests;

use App\Enums\KindOfMember;
use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Creating a user (administrators) or updating one (administrators, or members updating themselves).
 */
class UserRequest extends FormRequest
{
    use ValidatesMemberDetails;

    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user === null || $this->user()->can('update', $user);
    }

    /**
     * An unchecked checkbox isn't posted. Members updating themselves can't change their name, remark or kind of membership.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['incasso' => $this->boolean('incasso')]);

        $user = $this->route('user');

        if ($user instanceof User && ! $this->isAdministrator()) {
            $this->merge(Arr::only($user->attributesToArray(), ['firstname', 'preposition', 'lastname', 'remark', 'kind_of_member']));
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
            'incasso' => ['boolean'],
            'remark' => ['nullable'],
            'kind_of_member' => [Rule::requiredIf($this->isAdministrator()), Rule::enum(KindOfMember::class)],
        ];
    }

    private function isAdministrator(): bool
    {
        return $this->user()->can('manage-members');
    }
}
