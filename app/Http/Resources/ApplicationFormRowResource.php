<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm\ApplicationFormRow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A form question as the form components read it.
 *
 * @mixin ApplicationFormRow
 */
class ApplicationFormRowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'required' => $this->required,
            'id' => $this->id,
            'applicationFormRowOptions' => ApplicationFormRowOptionResource::collection($this->applicationFormRowOptions),
        ];
    }
}
