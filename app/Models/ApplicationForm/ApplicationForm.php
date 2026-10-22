<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationForm
 * @package App\Models\ApplicationForm
 */
#[Fillable([
    'name',
])]
class ApplicationForm extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return HasMany
     */
    public function applicationFormRows(): HasMany
    {
        return $this->hasMany(ApplicationFormRow::class, 'application_form_id');
    }

    /**
     * Updates the rows that have an id, adds those that don't and deletes the rows that aren't given.
     */
    public function syncRows(array $rows): void
    {
        $applicationFormRowIds = [];

        foreach ($rows as $rowData) {
            if (array_key_exists('id', $rowData) === true) {
                $row = ApplicationFormRow::find($rowData['id']);
                $row->update($rowData);
            } else {
                $row = new ApplicationFormRow($rowData);
                $row->application_form_id = $this->id;
                $row->required = array_key_exists('required', $rowData);
                $row->save();
            }
            $row->syncOptions($rowData['options'] ?? []);
            $applicationFormRowIds[] = $row->id;
        }

        $this->applicationFormRows()
            ->whereNotIn('id', $applicationFormRowIds)
            ->delete();
    }
}
