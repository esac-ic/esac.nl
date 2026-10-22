<?php
/**
 * Created by PhpStorm.
 * User: otherwise777
 * Date: 5/25/2017
 * Time: 22:20
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'text',
    'created_by',
    'score',
    'parent_id',
    'has_parent',
])]
class Zekering extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function children(): HasMany
    {
        return $this->hasMany(Zekering::class, 'parent_id', 'id')->whereColumn('id', '!=', 'parent_id')->orderBy('id', 'asc');
    }
}
