<?php

namespace App\Models\ApplicationForm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationFormRow
 * @package App\Models\ApplicationForm
 */
class ApplicationFormRow extends Model
{
    use SoftDeletes;

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
    const FORM_TYPE_NUMBER = 'number';

    /**
     * @var string
     */
    const FORM_TYPE_TEXT = 'text';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'application_form_id',
        'type',
        'required',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'required' => 'boolean',
    ];

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
}
