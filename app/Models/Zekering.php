<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'text',
    'parent_id',
])]
class Zekering extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'score' => 1,
    ];

    /**
     * @return HasMany<Zekering, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Zekering::class, 'parent_id')->oldest('id');
    }
}
