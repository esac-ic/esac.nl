<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRegistrationInfo extends Model
{
    use SoftDeletes;

    protected $table = 'user_registration_info';

    protected $fillable = [
        'intro_package',
        'shirt_size',
        'toprope_course',
        'intro_weekend_date',
    ];

    protected $dates = [
        'intro_weekend_date'
    ];

    protected $casts = [
        'toprope_course' => 'boolean',
        'intro_package' => 'boolean',
    ];
}
