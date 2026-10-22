<?php

namespace App\Http\Resources;

use App\Models\Zekering;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A zekering as the zekeringen page's JavaScript reads it, with the column names it was written for.
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
            'createdBy' => $this->created_by,
            'score' => $this->score,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'parent_id' => $this->parent_id,
            'has_parent' => $this->parent_id !== null,
            'deleted_at' => $this->deleted_at,
            'children' => self::collection($this->whenLoaded('children')),
        ];
    }
}
