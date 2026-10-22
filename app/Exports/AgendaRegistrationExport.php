<?php

namespace App\Exports;

use App\Models\AgendaItem;
use App\Services\AgendaApplicationFormService;
use Illuminate\Support\Collection;

class AgendaRegistrationExport extends SpreadsheetExport
{
    public function __construct(private readonly AgendaApplicationFormService $agendaApplicationFormService, private readonly AgendaItem $agendaItem) {}

    public function collection(): Collection
    {
        return $this->agendaApplicationFormService->getExportData($this->agendaItem);
    }

    public function title(): string
    {
        return 'Subscriptions';
    }

    public function headings(): array
    {
        $defaultValues = [
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
        ];

        return array_merge($defaultValues, $this->agendaItem->getApplicationForm->applicationFormRows->pluck('name')->all());
    }
}
