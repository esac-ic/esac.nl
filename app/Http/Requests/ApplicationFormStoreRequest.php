<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nl_name' => 'required',
            'en_name' => 'required',
            'rows.*.nl_name' => 'required',
            'rows.*.en_name' => 'required',
            'rows.*.type' => 'required',
        ];
    }
}
