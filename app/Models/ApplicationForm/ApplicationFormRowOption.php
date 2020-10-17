<?php

namespace App\Models\ApplicationForm;

use App\Text;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationFormRowOption
 * @package App\Models\ApplicationForm
 */
class ApplicationFormRowOption extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name_id',
        'value'
    ];

    public function applicationFormRowOptionName(): BelongsTo
    {
        return $this->belongsTo(Text::class, 'name_id');
    }
}
