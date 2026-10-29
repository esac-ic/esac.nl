<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'author' => ['required', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'max:10240'],
        ];
    }
}
