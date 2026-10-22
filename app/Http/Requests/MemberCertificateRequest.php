<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberCertificateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'certificate_id' => ['required', 'exists:certificates,id'],
        ];
    }
}
