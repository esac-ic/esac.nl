<?php
/**
 * Created by PhpStorm.
 * User: otherwise777
 * Date: 5/25/2017
 * Time: 22:20
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zekering extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'text',
        'createdBy',
        'score',
        'parent_id',
        'has_parent',
    ];

    public function children()
    {
        return $this->hasMany(Zekering::class, 'parent_id', 'id')->where('id', '!=', 'parent_id')->orderBy('id', 'asc');
    }
}
