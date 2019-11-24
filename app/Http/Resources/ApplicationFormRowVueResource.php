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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nameNL'   => $this->applicationFormRowName->NL_text,
            'nameEN'   => $this->applicationFormRowName->EN_text,
            'type'     => $this->type,
            'required' => $this->required,
            'id'       => $this->id,
        ];
    }
}
