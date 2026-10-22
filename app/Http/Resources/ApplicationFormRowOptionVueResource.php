<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm\ApplicationFormRowOption;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ApplicationFormRowOption
 */
class ApplicationFormRowOptionVueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
