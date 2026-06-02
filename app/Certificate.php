<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'abbreviation',
    ];

    public static function booted(){
        static::deleted(function($certificate){
            $certificate->users()->detach();
        });
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
