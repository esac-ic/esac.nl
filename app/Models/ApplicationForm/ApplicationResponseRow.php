<?php

namespace App\Models\ApplicationForm;
;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationResponseRow
 * @package App\Models\ApplicationForm
 */
class ApplicationResponseRow extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'application_response_id',
        'application_form_row_id',
        'value'
    ];

    /**
     * @return HasOne
     */
    public function getApplicationFormRow(): HasOne
    {
        return $this->hasOne(ApplicationFormRow::class, 'id', 'application_form_row_id')->withTrashed();
    }
}