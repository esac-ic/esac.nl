<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function rules(): array
    {
        $page = $this->route('page');
        // The URL name and content of a non-editable page can only be changed in the database.
        $fixed = Rule::excludeIf($page?->editable === false);

        return [
            'url_name' => [$fixed, 'required', 'max:255', Rule::unique('menu_items', 'url_name')->ignore($page)],
            'itemType' => ['required', Rule::in(['standAlone', 'subItem'])],
            'name' => ['required', 'max:255'],
            'content' => [$fixed, 'required'],
            'position' => ['required', 'integer', 'min:0'],
            'parentItem' => ['exclude_unless:itemType,subItem', 'required', Rule::exists('menu_items', 'id')],
            'login' => ['boolean'],
            'menu_item' => ['boolean'],
        ];
    }

    /**
     * An unchecked checkbox isn't posted.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['login' => $this->boolean('login'), 'menu_item' => $this->boolean('menu_item')]);
    }
}
