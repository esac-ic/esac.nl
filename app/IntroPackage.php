<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntroPackage extends Model
{
    protected $casts = [
        'deadline' => 'date',
    ];

    protected $fillable = [
        'name',
        'deadline',
        'application_form_id',
    ];

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class);
    }
}
