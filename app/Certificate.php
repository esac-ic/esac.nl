<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'duration',
        'abbreviation'
    ];

    public function certificateName(){
        return $this->hasOne('App\Text', 'id', 'name');
    }

}
