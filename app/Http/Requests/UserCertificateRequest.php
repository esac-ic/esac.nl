<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCertificateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'certificate_id' => 'required|numeric|min:0',
        ];
    }
}
