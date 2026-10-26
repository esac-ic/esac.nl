<?php

namespace App\Http\Requests;

use App\Rules\MaxMegapixels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class HeaderSlideRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'heading' => ['required', 'max:255'],
            'message' => ['required', 'max:255'],
            'link' => ['required', 'max:255'],
            'image' => [Rule::requiredIf($this->route('header_slide') === null), File::image()->max('10mb'), new MaxMegapixels(24)],
        ];
    }
}
