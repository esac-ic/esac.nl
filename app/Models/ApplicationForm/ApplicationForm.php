<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationForm
 * @package App\Models\ApplicationForm
 */
class ApplicationForm extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany
     */
    public function applicationFormRows(): HasMany
    {
        return $this->hasMany(ApplicationFormRow::class, 'application_form_id');
    }
}
