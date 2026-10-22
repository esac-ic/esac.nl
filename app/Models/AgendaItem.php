<?php

namespace App\Models;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'text',
    'short_description',
    'subscription_end_date',
    'start_date',
    'end_date',
    'image_url',
    'category',
    'climbing_activity',
])]
class AgendaItem extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'climbing_activity' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'subscription_end_date' => 'datetime',
        ];
    }

    /**
     * Events that start today or later, the first one first.
     */
    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->whereDate('start_date', '>=', Carbon::now())->orderBy('start_date', 'ASC');
    }

    /**
     * Sets the dates, registration form and climbing flag from the event form's data.
     */
    public function fillFromForm(array $data): static
    {
        $this->start_date = Carbon::parse($data['start_date'])->startOfMinute();
        $this->end_date = Carbon::parse($data['end_date'])->startOfMinute();

        if ($data['applicationForm'] != 0) {
            $this->subscription_end_date = Carbon::parse($data['subscription_end_date'])->startOfMinute();
            $this->application_form_id = $data['applicationForm'];
        } else {
            $this->subscription_end_date = null;
            $this->application_form_id = null;
        }

        $this->climbing_activity = array_key_exists('climbing_activity', $data);

        return $this;
    }

    /**
     * Saves a copy of the event, with a copy of its image, created by the given user.
     */
    public function copyFor(User $creator): AgendaItem
    {
        $newAgendaItem = $this->replicate();
        $newAgendaItem->created_by = $creator->id;
        $newAgendaItem->save();

        if ($this->image_url !== null && $this->image_url !== '') {
            $oldPath = $this->image_url;
            $newPath = str_replace($this->id, $newAgendaItem->id, $oldPath);
            Storage::disk('public')->copy($oldPath, $newPath);
            $newAgendaItem->image_url = $newPath;
            $newAgendaItem->save();
        }

        return $newAgendaItem;
    }

    /**
     * Registers the user for the event with their answers, keyed by the form row id.
     */
    public function register(int $userId, array $answers): void
    {
        $applicationResponse = new ApplicationResponse;
        $applicationResponse->agenda_id = $this->id;
        $applicationResponse->inschrijf_form_id = $this->application_form_id;
        $applicationResponse->user_id = $userId;
        $applicationResponse->save();

        foreach ($answers as $key => $value) {
            $applicationResponseRow = new ApplicationResponseRow;
            $applicationResponseRow->application_response_id = $applicationResponse->id;
            $applicationResponseRow->application_form_row_id = $key;
            $applicationResponseRow->value = is_array($value) ? implode(',', $value) : $value ?? '';
            $applicationResponseRow->save();
        }
    }

    public function getApplicationForm(): BelongsTo
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id');
    }

    public function agendaItemCategory(): BelongsTo
    {
        return $this->belongsTo(AgendaItemCategory::class, 'category')->withTrashed();
    }

    public function getCreatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getImageUrl(): string
    {
        if ($this->image_url != '') {
            return Storage::disk('public')->url($this->image_url);
        } else {
            return '/img/default_agenda_item_cover.jpg';
        }
    }

    public function getApplicationFormResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class, 'agenda_id');
    }

    public function canRegister(): bool
    {
        return ! ($this->subscription_end_date < Carbon::now());
    }
}
