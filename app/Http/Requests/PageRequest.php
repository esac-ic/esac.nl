<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route as RouteDefinition;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    /**
     * A page's URL can't take the first segment of the site's own routes or of a public folder, e.g. /admin or /build.
     */
    private function reservedSlugs(): array
    {
        return collect(Route::getRoutes()->getRoutes())
            ->map(fn (RouteDefinition $route) => Str::before($route->uri(), '/'))
            ->merge(array_map(basename(...), File::directories(public_path())))
            ->reject(fn (string $segment) => Str::startsWith($segment, '{'))
            ->unique()
            ->values()
            ->all();
    }

    public function rules(): array
    {
        $page = $this->route('page');

        return [
            'slug' => [
                Rule::excludeIf($page?->hasFixedSlug() ?? false),
                'required',
                'max:255',
                // Older pages keep their slug until it's changed
                Rule::when($this->input('slug') !== $page?->slug, ['lowercase', 'alpha_dash:ascii', Rule::notIn($this->reservedSlugs())]),
                Rule::unique('pages', 'slug')->ignore($page),
            ],
            'name' => ['required', 'max:255'],
            // The content of a non-editable page can only be changed in the database.
            'body' => [Rule::excludeIf($page?->editable === false), 'required'],
            // The menu has two levels: a sub page's parent is a top-level page, and a page with sub pages stays top-level.
            'parent_id' => [Rule::prohibitedIf($page?->children()->exists() ?? false), 'nullable', Rule::exists('pages', 'id')->whereNull('parent_id')->when($page, fn ($rule) => $rule->whereNot('id', $page->id))],
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
