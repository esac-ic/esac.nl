<?php

namespace App\Models\User;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRegistrationInfo extends Model
{
    protected $table = 'user_registration_info';

    protected $fillable = [
        'package_type',
        'shirt_size',
        'intro_weekend',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
