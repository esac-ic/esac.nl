<?php

namespace App\Http\Requests;

use App\Enums\SettingKey;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'settings' => ['required', 'array:'.collect(SettingKey::cases())->pluck('value')->implode(',')],
            'settings.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
