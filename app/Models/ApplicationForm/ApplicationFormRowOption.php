<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationFormRowOption
 * @package App\Models\ApplicationForm
 */
#[Fillable([
    'name',
    'value',
])]
class ApplicationFormRowOption extends Model
{
    use HasFactory, SoftDeletes;
}
