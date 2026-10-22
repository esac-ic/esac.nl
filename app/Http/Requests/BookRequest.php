<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'year' => 'nullable|integer',
            'type' => 'required|max:255',
            'country' => 'required|max:255',
            'code' => 'required|max:255',
        ];
    }
}
