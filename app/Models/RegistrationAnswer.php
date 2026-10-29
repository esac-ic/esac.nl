<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * An answer with its question as it was asked, so it reads the same after the form changes.
 */
#[Fillable([
    'registration_id',
    'registration_form_field_id',
    'question',
    'value',
])]
class RegistrationAnswer extends Model {}
