<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationForm extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name'
    ];

    public function applicationFormName(){
        return $this->hasOne('App\Text', 'id', 'name')->withTrashed();
    }

    public function getApplicationFormRows(){
        return $this->hasMany('App\ApplicationFormRow','application_form_id')->withTrashed();
    }

    public function getActiveApplicationFormRows(){
        return $this->hasMany('App\ApplicationFormRow','application_form_id');
    }
}
