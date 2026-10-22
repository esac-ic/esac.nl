<?php

namespace App\Models\ApplicationForm;

use App\Models\AgendaItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'agenda_id',
    'inschrijf_form_id',
    'user_id',
])]
class ApplicationResponse extends Model
{
    use HasFactory, SoftDeletes;

    public function getApplicationFormResponseRows(): HasMany
    {
        return $this->hasMany(ApplicationResponseRow::class, 'application_response_id')->withTrashed();
    }

    public function agendaItem(): BelongsTo
    {
        return $this->belongsTo(AgendaItem::class, 'agenda_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
