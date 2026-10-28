<?php

namespace App\Http\Requests;

use App\Models\MenuItem;
use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function rules(): array
    {
        $page = $this->route('page');

        // An existing page is only validated when its URL name changes.
        if ($page instanceof MenuItem && ! ($page->editable && $page->url_name != $this->input('url_name'))) {
            return [];
        }

        return [
            'url_name' => 'required|max:255|unique:menu_items',
            'itemType' => 'required',
            'name' => 'required|max:255',
            'content' => 'required',
            'afterItem' => 'required',
            'parentItem' => 'required_if:itemType,subItem',
        ];
    }
}
