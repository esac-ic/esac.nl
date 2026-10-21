<?php

namespace App\Http\Requests;

use App\Enums\Role;
use App\Http\Requests\Concerns\ValidatesMemberDetails;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * Members updating themselves can't change their name, remark or kind of membership.
     */
    protected function prepareForValidation(): void
    {
        $user = $this->route('user');

        if ($user instanceof User && ! $this->isAdministrator()) {
            $this->merge($user->only(['firstname', 'preposition', 'lastname', 'remark', 'kind_of_member']));
        }
    }

    public function rules(): array
    {
        return [
            ...$this->memberDetailRules($this->route('user')?->id),
            'kind_of_member' => Rule::requiredIf($this->isAdministrator()),
        ];
    }

    private function isAdministrator(): bool
    {
        return $this->user()->hasRole(Role::Administrator->value);
    }
}
