<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaItemCategorie extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];

    public function categorieName(){
        return $this->hasOne('App\Text', 'id', 'name')->withTrashed();
    }
}
