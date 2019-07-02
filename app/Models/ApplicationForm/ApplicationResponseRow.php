<?php

namespace App\Models\ApplicationForm;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationResponseRow extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'application_response_id',
        'application_form_row_id',
        'value'
    ];
    public function getApplicationFormResponseRowName(){
        return $this->hasOne('App\ApplicationFormRow','id','application_form_row_id')->withTrashed();
    }

}