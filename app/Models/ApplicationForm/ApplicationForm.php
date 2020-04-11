<?php

namespace App\Models\ApplicationForm;

use App\Text;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'name'
    ];

    /**
     * @return HasOne
     */
    public function applicationFormName(): HasOne{
        return $this->hasOne(Text::class, 'id', 'name')->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function applicationFormRows(): HasMany{
        return $this->hasMany(ApplicationFormRow::class,'application_form_id');
    }
}
