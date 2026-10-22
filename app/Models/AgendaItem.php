<?php

namespace App\Models;

use App\Casts\DisplayDateTime;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
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
            'start_date' => DisplayDateTime::class,
            'end_date' => DisplayDateTime::class,
            'subscription_end_date' => DisplayDateTime::class,
        ];
    }

    /**
     * Events that start today or later, the first one first.
     */
    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->where('start_date', '>=', today(config('app.display_timezone'))->utc())->orderBy('start_date');
    }

    /**
     * Sets the registration form and climbing flag from the event form's validated data.
     */
    public function fillFromForm(array $data): static
    {
        $this->application_form_id = $data['applicationForm'] ?? null;
        $this->subscription_end_date = $data['subscription_end_date'] ?? null;
        $this->climbing_activity = $data['climbing_activity'] ?? false;

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

        if ($this->image_url !== '') {
            // Named after the new item, so each item has its own file to replace or delete.
            $newPath = 'agendaItem/'.$newAgendaItem->id.'-thumbnail.'.pathinfo($this->image_url, PATHINFO_EXTENSION);
            Storage::disk('public')->copy($this->image_url, $newPath);
            $newAgendaItem->image_url = $newPath;
            $newAgendaItem->save();
        }

        return $newAgendaItem;
    }

    /**
     * Registers the user for the event with their validated answers, keyed by the form row id.
     */
    public function register(int $userId, array $answers): void
    {
        DB::transaction(function () use ($userId, $answers) {
            $applicationResponse = $this->getApplicationFormResponses()->create([
                'inschrijf_form_id' => $this->application_form_id,
                'user_id' => $userId,
            ]);

            // Unanswered optional questions get no row.
            $applicationResponse->getApplicationFormResponseRows()->createMany(
                collect($answers)
                    ->whereNotNull()
                    ->map(fn ($value, $rowId) => [
                        'application_form_row_id' => $rowId,
                        'value' => is_array($value) ? implode(',', $value) : $value,
                    ])
                    ->values(),
            );
        });
    }

    /**
     * @return BelongsTo<ApplicationForm, $this>
     */
    public function getApplicationForm(): BelongsTo
    {
        return $this->belongsTo(ApplicationForm::class, 'application_form_id');
    }

    /**
     * @return BelongsTo<AgendaItemCategory, $this>
     */
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

    /**
     * @return HasMany<ApplicationResponse, $this>
     */
    public function getApplicationFormResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class, 'agenda_id');
    }

    public function isRegistered(User $user): bool
    {
        return $this->getApplicationFormResponses()
            ->whereBelongsTo($user)
            ->where('inschrijf_form_id', $this->application_form_id)
            ->exists();
    }

    public function canRegister(): bool
    {
        return ! ($this->subscription_end_date < Carbon::now());
    }
}
