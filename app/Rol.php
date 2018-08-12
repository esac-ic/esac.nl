<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];

    public function text(){
        return $this->hasOne('App\Text', 'id', 'name')->withTrashed();
    }
}
