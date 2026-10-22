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
            'itemType' => 'required',
            'name' => 'required|max:255',
            'content' => [$fixed, 'required'],
            'afterItem' => 'required',
            'parentItem' => 'required_if:itemType,subItem',
        ];
    }
}
