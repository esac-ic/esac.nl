<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationFormRowOptionVueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'nameNL' => $this->applicationFormRowOptionName->NL_text,
            'nameEN' => $this->applicationFormRowOptionName->EN_text,
            'value'  => $this->value,
        ];
    }
}
