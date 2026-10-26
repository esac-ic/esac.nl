<?php

namespace App\Http\Requests;

use App\Rules\MaxMegapixels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class SponsorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'url' => ['required', 'url', 'max:255'],
            'logo' => [Rule::requiredIf($this->route('sponsor') === null), File::image()->max('10mb'), new MaxMegapixels(24)],
        ];
    }
}
