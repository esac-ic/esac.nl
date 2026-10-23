<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    /**
     * The first URL segments of the site's own routes, the old ones that redirect included, which a page's slug can't take.
     */
    private const array RESERVED_SLUGS = [
        'account', 'admin', 'agenda', 'api', 'events', 'forms', 'home', 'ical', 'library', 'login', 'logout',
        'memberlist', 'members', 'news', 'password', 'sign-up', 'signup', 'storage', 'up', 'user', 'zekeringen',
    ];

    public function rules(): array
    {
        $page = $this->route('page');

        return [
            'slug' => [
                Rule::excludeIf($page?->hasFixedSlug() ?? false),
                'required',
                'max:255',
                'lowercase',
                'alpha_dash:ascii',
                Rule::notIn(self::RESERVED_SLUGS),
                Rule::unique('menu_items', 'slug')->ignore($page),
            ],
            'name' => ['required', 'max:255'],
            // The content of a non-editable page can only be changed in the database.
            'content' => [Rule::excludeIf($page?->editable === false), 'required'],
            // The menu has two levels: a sub page's parent is a top-level page, and a page with sub pages stays top-level.
            'parent_id' => [Rule::prohibitedIf($page?->children()->exists() ?? false), 'nullable', Rule::exists('menu_items', 'id')->whereNull('parent_id')->when($page, fn ($rule) => $rule->whereNot('id', $page->id))],
            // The site's own routes decide who can see the pages with a fixed slug.
            'requires_login' => [Rule::excludeIf($page?->hasFixedSlug() ?? false), 'boolean'],
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
