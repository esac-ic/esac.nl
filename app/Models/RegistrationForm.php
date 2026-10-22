<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
])]
class RegistrationForm extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return HasMany<RegistrationFormField, $this>
     */
    public function fields(): HasMany
    {
        return $this->hasMany(RegistrationFormField::class, 'registration_form_id');
    }

    /**
     * Updates the fields that have an id, adds those that don't and deletes the fields that aren't given.
     */
    public function syncFields(array $fields): void
    {
        $fieldIds = [];

        foreach ($fields as $fieldData) {
            if (array_key_exists('id', $fieldData) === true) {
                $field = $this->fields()->findOrFail($fieldData['id']);
            } else {
                $field = new RegistrationFormField;
                $field->registration_form_id = $this->id;
            }
            $field->fill($fieldData);
            $field->required = $fieldData['required'] ?? false;
            $field->save();
            $field->syncOptions($fieldData['options'] ?? []);
            $fieldIds[] = $field->id;
        }

        $this->fields()
            ->whereNotIn('id', $fieldIds)
            ->delete();
    }
}
