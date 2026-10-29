<?php

namespace App\Models;

use App\Enums\FormFieldType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

#[Fillable([
    'name',
    'registration_form_id',
    'type',
    'required',
])]
class RegistrationFormField extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return HasMany<RegistrationFormFieldOption, $this>
     */
    public function options(): HasMany
    {
        return $this->hasMany(RegistrationFormFieldOption::class);
    }

    /**
     * Updates the options that have an id, adds those that don't and deletes the options that aren't given.
     */
    public function syncOptions(array $options): void
    {
        $optionIds = [];

        foreach ($options as $optionData) {
            $option = $this->options()->findOrNew($optionData['id'] ?? null);
            $option->fill(Arr::only($optionData, ['name', 'value']))->save();
            $optionIds[] = $option->id;
        }

        $this->options()
            ->whereNotIn('id', $optionIds)
            ->delete();
    }

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'type' => FormFieldType::class,
        ];
    }
}
