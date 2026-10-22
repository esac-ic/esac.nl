<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Registration;
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
        return $this->event->registrations()->with(['user', 'answers'])->get();
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
            ...$this->fields()->map(fn (RegistrationFormField $field) => $registration->answers->firstWhere('registration_form_field_id', $field->id)?->value),
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
            ...$this->fields()->pluck('name'),
        ];
    }

    /**
     * @return Collection<int, RegistrationFormField>
     */
    private function fields(): Collection
    {
        return $this->event->registrationForm->fields;
    }
}
