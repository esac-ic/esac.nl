<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationFormRow
 * @package App\Models\ApplicationForm
 */
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
     *  @var string
     */
    const FORM_TYPE_SELECT = 'select';

    /**
     * @var string
     */
    const FORM_TYPE_RADIO = 'radio';

    /**
     *  @var string
     */
    const FORM_TYPE_TEXT_BOX = 'textBox';

    /**
     *  @var string
     */
    const FORM_TYPE_CHECK_BOX = 'checkbox';
    /**
     *  @var string
     */
    const FORM_TYPE_MULTI_CHECK_BOX = 'checkboxen';

    /**
     *  @var string
     */
    const FORM_TYPE_NUMBER = 'number';

    /**
     * @var string
     */
    const FORM_TYPE_TEXT = 'text';

    /**
     * @var array
     */
    private $_inputfiels = null;

    /**
     * @return HasMany
     */
    public function applicationFormRowOptions(): HasMany
    {
        return $this->hasMany(ApplicationFormRowOption::class);
    }
    /**
     * @return array
     */
    protected function casts(): array
    {
        return [
            'required' => 'boolean',
        ];
    }
}
