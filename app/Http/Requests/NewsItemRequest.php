<?php

namespace App\Http\Requests;

use App\Rules\MaxMegapixels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class NewsItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'author' => ['required', 'max:255'],
            'image' => ['nullable', File::image()->max('10mb'), new MaxMegapixels(24)],
        ];
    }
}
