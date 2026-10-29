<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Registration;
use App\Models\RegistrationAnswer;
use App\Models\RegistrationFormField;
use Illuminate\Support\Collection;

/**
 * The registered members of an event with their answers, one column per field.
 */
class EventRegistrationsExport extends SpreadsheetExport
{
    public function __construct(private readonly Event $event) {}

    public function collection(): Collection
    {
        // Anonymised registrations have no one to export
        return $this->event->registrations()->whereNotNull('user_id')->with(['user', 'answers'])->get();
    }

    /**
     * @param  Registration  $registration
     */
    public function map(mixed $registration): array
    {
        $user = $registration->user;

        return [
            $user->first_name,
            $user->preposition,
            $user->last_name,
            $user->street,
            $user->house_number,
            $user->city,
            $user->zipcode,
            $user->email,
            $user->phone_number,
            $user->birthday?->format('d-m-Y'),
            ...$this->questions()->map(fn (array $column) => $registration->answers
                ->first(fn (RegistrationAnswer $answer) => [$answer->registration_form_field_id, $answer->question] === $column)?->value),
        ];
    }

    public function title(): string
    {
        return 'Registrations';
    }

    public function headings(): array
    {
        return [
            'First name',
            'Preposition',
            'Last name',
            'Street',
            'House number',
            'City',
            'Postal code',
            'Email address',
            'Phone number',
            'Date of birth',
            ...$this->questions()->pluck(1),
        ];
    }

    /**
     * One column per question as it was asked, so a renamed question keeps its answers under their own wording and
     * questions with the same wording stay apart: the answered questions, then the form's questions nobody answered yet.
     *
     * @return Collection<int, array{int, ?string}>
     */
    private function questions(): Collection
    {
        return once(fn () => RegistrationAnswer::query()
            ->whereIn('registration_id', $this->event->registrations()->select('id'))
            ->distinct()
            ->get(['registration_form_field_id', 'question'])
            ->toBase()
            ->map(fn (RegistrationAnswer $answer) => [$answer->registration_form_field_id, $answer->question])
            ->merge(collect($this->event->registrationForm?->fields)->map(fn (RegistrationFormField $field) => [$field->id, $field->name]))
            ->unique(fn (array $column) => json_encode($column))
            ->sortBy(fn (array $column) => $column[0])
            ->values());
    }
}
