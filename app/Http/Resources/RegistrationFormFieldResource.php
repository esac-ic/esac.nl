<?php

namespace App\Http\Resources;

use App\Models\RegistrationFormField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A registration form field as the Vue form components read it, with the keys they were written for.
 *
 * @mixin RegistrationFormField
 */
class RegistrationFormFieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'required' => $this->required,
            'id' => $this->id,
            'applicationFormRowOptions' => RegistrationFormFieldOptionResource::collection($this->options),
        ];
    }
}
