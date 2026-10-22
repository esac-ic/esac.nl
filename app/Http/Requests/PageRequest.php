<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function rules(): array
    {
        $page = $this->route('page');
        // The slug and content of a non-editable page can only be changed in the database.
        $fixed = Rule::excludeIf($page?->editable === false);

        return [
            'slug' => [$fixed, 'required', 'max:255', Rule::unique('menu_items', 'slug')->ignore($page)],
            'menu_level' => ['required', Rule::in(['top', 'child'])],
            'name' => ['required', 'max:255'],
            'content' => [$fixed, 'required'],
            'position' => ['required', 'integer', 'min:0'],
            'parent_id' => ['exclude_unless:menu_level,child', 'required', Rule::exists('menu_items', 'id')],
            'requires_login' => ['boolean'],
            'show_in_menu' => ['boolean'],
        ];
    }

    /**
     * An unchecked checkbox isn't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['requires_login' => $this->boolean('requires_login'), 'show_in_menu' => $this->boolean('show_in_menu')]);
    }
}
