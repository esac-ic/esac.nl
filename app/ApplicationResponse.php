<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationResponse extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'agenda_id',
        'inschrijf_form_id',
        'user_id',
    ];

    /**
     * retrieves the custom rows from the table
     */
    public function getApplicationFormResponseRows(){
        return $this->hasMany('App\ApplicationResponseRow','application_response_id')->withTrashed();
    }
    public function getApplicationResponseUser(){
        return $this->hasOne('App\User','id','user_id');
    }
}