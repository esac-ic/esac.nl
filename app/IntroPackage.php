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

    public function packageName()
    {
        return $this->hasOne(Text::class, 'id', 'name');
    }

    public function applicationForm()
    {
        return $this->belongsTo(ApplicationForm::class);
    }
}
