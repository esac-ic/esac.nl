<?php

namespace App\Models\ApplicationForm;

use App\AgendaItem;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationResponse
 * @package App\Models\ApplicationForm
 */
class ApplicationResponse extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'agenda_id',
        'inschrijf_form_id',
        'user_id',
    ];

    /**
     * retrieves the custom rows from the table
     */
    public function getApplicationFormResponseRows(): HasMany
    {
        return $this->hasMany(ApplicationResponseRow::class, 'application_response_id')->withTrashed();
    }

    /**
     * @return HasOne
     */
    public function getApplicationResponseUser(): HasOne
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function agendaItem(): BelongsTo
    {
        return $this->belongsTo(AgendaItem::class, 'agenda_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
