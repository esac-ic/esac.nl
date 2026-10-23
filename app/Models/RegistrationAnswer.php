<?php

namespace App\Models;

use App\Enums\FormFieldType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'registration_id',
    'registration_form_field_id',
    'value',
])]
class RegistrationAnswer extends Model
{
    use SoftDeletes;

    /**
     * @return BelongsTo<RegistrationFormField, $this>
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(RegistrationFormField::class, 'registration_form_field_id')->withTrashed();
    }

    protected function formattedValue(): Attribute
    {
        return Attribute::make(get: fn () => match ($this->field->type) {
            FormFieldType::Checkbox => $this->value === 'on' ? 'Yes' : 'No',
            default => $this->value,
        });
    }
}
