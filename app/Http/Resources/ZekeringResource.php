<?php

namespace App\Http\Resources;

use App\Models\Zekering;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
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
