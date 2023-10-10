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
            'name' => $this->name,
            'type' => $this->type,
            'required' => $this->required,
            'id' => $this->id,
            'applicationFormRowOptions' => ApplicationFormRowOptionVueResource::collection($this->applicationFormRowOptions),
        ];
    }
}
