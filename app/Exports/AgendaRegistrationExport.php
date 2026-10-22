<?php

namespace App\Exports;

use App\Models\AgendaItem;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\ApplicationForm\ApplicationResponse;
use Illuminate\Support\Collection;

/**
 * The registered members of an event with their answers, one column per question.
 */
class AgendaRegistrationExport extends SpreadsheetExport
{
    public function __construct(private readonly AgendaItem $agendaItem) {}

    public function collection(): Collection
    {
        // Anonymised registrations have no one to export
        return $this->agendaItem->registrations()->whereNotNull('user_id')->with(['user', 'rows'])->get();
    }

    /**
     * @param  ApplicationResponse  $registration
     */
    public function map(mixed $registration): array
    {
        $user = $registration->user;

        return [
            $user->firstname,
            $user->preposition,
            $user->lastname,
            $user->street,
            $user->house_number,
            $user->city,
            $user->zipcode,
            $user->email,
            $user->phone_number,
            $user->birthday?->format('d-m-Y'),
            ...$this->questions()->map(fn (ApplicationFormRow $question) => $registration->rows->firstWhere('application_form_row_id', $question->id)?->value),
        ];
    }

    public function title(): string
    {
        return 'Subscriptions';
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
            'Zip code',
            'Email address',
            'Phone number',
            'Date of birth',
            ...$this->questions()->pluck('name'),
        ];
    }

    /**
     * @return Collection<int, ApplicationFormRow>
     */
    private function questions(): Collection
    {
        return $this->agendaItem->applicationForm->applicationFormRows;
    }
}
