<?php

namespace App\Models;

use App\Casts\DisplayDateTime;
use App\Casts\SanitizedHtml;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'body',
    'short_description',
    'registration_deadline',
    'start_date',
    'end_date',
    'image_path',
    'event_category_id',
    'shows_certificates',
])]
class Event extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'body' => SanitizedHtml::class,
            'shows_certificates' => 'boolean',
            'start_date' => DisplayDateTime::class,
            'end_date' => DisplayDateTime::class,
            'registration_deadline' => DisplayDateTime::class,
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
     * Sets the registration form, deadline and certificates flag from the event form's validated data.
     */
    public function fillRegistrationSettings(array $data): static
    {
        $this->registration_form_id = $data['registration_form_id'] ?? null;
        $this->registration_deadline = $data['registration_deadline'] ?? null;
        $this->shows_certificates = $data['shows_certificates'];

        return $this;
    }

    /**
     * Saves a copy of the event, with a copy of its image, created by the given member.
     */
    public function copyFor(User $creator): Event
    {
        $copy = $this->replicate();
        $copy->created_by = $creator->id;
        $copy->save();

        if ($this->image_path !== '') {
            // Named after the new event, so each event has its own file to replace or delete.
            $copyImagePath = 'agendaItem/'.$copy->id.'-thumbnail.'.pathinfo($this->image_path, PATHINFO_EXTENSION);
            Storage::disk('public')->copy($this->image_path, $copyImagePath);
            $copy->image_path = $copyImagePath;
            $copy->save();
        }

        return $copy;
    }

    /**
     * Registers the member for the event with their validated answers, keyed by the field id.
     */
    public function register(int $userId, array $answers): void
    {
        DB::transaction(function () use ($userId, $answers) {
            $registration = $this->registrations()->create([
                'registration_form_id' => $this->registration_form_id,
                'user_id' => $userId,
            ]);

            // Unanswered optional fields get no answer.
            $registration->answers()->createMany(
                collect($answers)
                    ->whereNotNull()
                    ->map(fn ($value, $fieldId) => [
                        'registration_form_field_id' => $fieldId,
                        'value' => is_array($value) ? implode(',', $value) : $value,
                    ])
                    ->values(),
            );
        });
    }

    /**
     * @return BelongsTo<RegistrationForm, $this>
     */
    public function registrationForm(): BelongsTo
    {
        return $this->belongsTo(RegistrationForm::class, 'registration_form_id');
    }

    /**
     * @return BelongsTo<EventCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id')->withTrashed();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The URL of the event's image, or of the default cover when it has none.
     *
     * @return Attribute<string, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image_path != ''
            ? Storage::disk('public')->url($this->image_path)
            : '/img/default-event-cover.jpg');
    }

    /**
     * @return HasMany<Registration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'event_id');
    }

    public function hasRegistrationFrom(User $user): bool
    {
        return $this->registrations()
            ->whereBelongsTo($user)
            ->exists();
    }

    public function isRegistrationOpen(): bool
    {
        return $this->registration_deadline?->isFuture() ?? false;
    }
}
