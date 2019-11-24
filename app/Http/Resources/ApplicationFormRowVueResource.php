<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ApplicationFormRowVueResource
 *
 * @mixin ApplicationFormRow
 * @package App\Http\Resources
 *
 */
class ApplicationFormRowVueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nameNl' => $this->applicationFormRowName->NL_text,
            'nameEn' => $this->applicationFormRowName->EN_text,
            'type' => $this->type,
            'required' => $this->required,
        ];
    }
}
