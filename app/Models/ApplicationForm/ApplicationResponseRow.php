<?php

namespace App\Models\ApplicationForm;

use App\Enums\FormRowType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'application_response_id',
    'application_form_row_id',
    'value',
])]
class ApplicationResponseRow extends Model
{
    use SoftDeletes;

    /**
     * @return BelongsTo<ApplicationFormRow, $this>
     */
    public function formRow(): BelongsTo
    {
        return $this->belongsTo(ApplicationFormRow::class, 'application_form_row_id')->withTrashed();
    }

    protected function formattedValue(): Attribute
    {
        return Attribute::make(get: fn () => match ($this->formRow->type) {
            FormRowType::CheckBox => $this->value === 'on' ? 'Yes' : 'No',
            default => $this->value,
        });
    }
}
