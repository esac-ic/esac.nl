<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ApplicationFormRowVueResource
 *
 * @mixin ApplicationFormRow
 */
class ApplicationFormRowVueResource extends JsonResource
{
    public function toArray(Request $request): array
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
