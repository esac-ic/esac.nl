<?php

namespace App\Http\Requests;

use App\Enums\SettingKey;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'setting' => ['required', 'array:'.collect(SettingKey::cases())->pluck('value')->implode(',')],
            'setting.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
