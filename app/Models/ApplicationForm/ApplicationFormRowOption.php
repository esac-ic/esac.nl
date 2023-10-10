<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationFormRowOption
 * @package App\Models\ApplicationForm
 */
class ApplicationFormRowOption extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
    ];
}
