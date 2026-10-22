<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'short_description' => 'required|max:100',
            'text' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg',
            'category' => 'required|numeric|min:1',
            'applicationForm' => 'required|numeric|min:0',
            'end_date' => 'required|date',
            'start_date' => 'required|date',
        ];
    }
}
