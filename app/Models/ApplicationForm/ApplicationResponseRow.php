<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationResponseRow
 * @package App\Models\ApplicationForm
 */
#[Fillable([
    'id',
    'application_response_id',
    'application_form_row_id',
    'value',
])]
class ApplicationResponseRow extends Model
{
    use SoftDeletes;

    /**
     * @return HasOne
     */
    public function getApplicationFormRow(): HasOne
    {
        return $this->hasOne(ApplicationFormRow::class, 'id', 'application_form_row_id')->withTrashed();
    }

    protected function formattedValue(): Attribute
    {
        return Attribute::make(get: fn () => match ($this->getApplicationFormRow->type) {
            ApplicationFormRow::FORM_TYPE_CHECK_BOX => $this->value === 'on' ? 'Yes' : 'No',
            default => $this->value,
        });
    }
}
