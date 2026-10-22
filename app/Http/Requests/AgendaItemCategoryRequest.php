<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaItemCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
