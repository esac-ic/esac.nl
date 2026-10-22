<?php

namespace App\Models\ApplicationForm;

use App\Enums\FormRowType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'application_form_id',
    'type',
    'required',
])]
class ApplicationFormRow extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return HasMany<ApplicationFormRowOption, $this>
     */
    public function applicationFormRowOptions(): HasMany
    {
        return $this->hasMany(ApplicationFormRowOption::class);
    }

    /**
     * Updates the options that have an id, adds those that don't and deletes the options that aren't given.
     */
    public function syncOptions(array $options): void
    {
        $optionIds = [];

        foreach ($options as $optionData) {
            if (array_key_exists('id', $optionData)) {
                $option = $this->applicationFormRowOptions()->findOrFail($optionData['id']);
                $option->update($optionData);
            } else {
                $option = $this->applicationFormRowOptions()->create($optionData);
            }
            $optionIds[] = $option->id;
        }

        $this->applicationFormRowOptions()
            ->whereNotIn('id', $optionIds)
            ->delete();
    }

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'type' => FormRowType::class,
        ];
    }
}
