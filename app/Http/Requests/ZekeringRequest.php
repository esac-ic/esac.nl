<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZekeringRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required'],
            'parent_id' => ['nullable', Rule::exists('zekerings', 'id')],
        ];
    }
}
