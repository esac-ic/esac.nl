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
            'name' => ['required', 'max:255'],
            'content' => [$fixed, 'required'],
            // The menu has two levels: a sub page's parent is a top-level page, and a page with sub pages stays top-level.
            'parent_id' => [Rule::prohibitedIf($page?->children()->exists() ?? false), 'nullable', Rule::exists('menu_items', 'id')->whereNull('parent_id')->when($page, fn ($rule) => $rule->whereNot('id', $page->id))],
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
