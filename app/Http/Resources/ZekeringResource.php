<?php

namespace App\Http\Resources;

use App\Models\Zekering;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A zekering as the zekeringen page's JavaScript reads it.
 *
 * @mixin Zekering
 */
class ZekeringResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'children' => self::collection($this->whenLoaded('children')),
        ];
    }
}
