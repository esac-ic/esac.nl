<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materiaal extends Model
{
    //

    protected $fillable = [
        'name',
        'type',
        'season'
    ];

    protected $primaryKey = 'materiaal_id';
}
