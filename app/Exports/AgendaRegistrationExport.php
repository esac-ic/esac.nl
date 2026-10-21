<?php

namespace App\Exports;

use App\Models\AgendaItem;
use App\Services\AgendaApplicationFormService;
use Illuminate\Support\Collection;

/**
 * Class AgendaRegistrationExport
 * @package App\Exports
 */
class AgendaRegistrationExport extends SpreadsheetExport
{
    /**
     * @var AgendaApplicationFormService
     */
    private AgendaApplicationFormService $agendaApplicationFormService;

    /**
     * @var AgendaItem
     */
    private AgendaItem $agendaItem;

    /**
     * AgendaRegistrationExport constructor.
     * @param AgendaApplicationFormService $agendaApplicationFormService
     * @param AgendaItem $agendaItem
     */
    public function __construct(AgendaApplicationFormService $agendaApplicationFormService, AgendaItem $agendaItem)
    {
        $this->agendaApplicationFormService = $agendaApplicationFormService;
        $this->agendaItem = $agendaItem;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return $this->agendaApplicationFormService->getExportData($this->agendaItem);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Subscriptions';
    }

    /**
     * @return array
     */
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

        $formQuestions = [];
        $rows = $this->agendaItem->getApplicationForm->applicationFormRows;
        foreach ($rows as $row) {
            $formQuestions[] = $row->name;
        }

        return array_merge($defaultValues, $formQuestions);

    }
}
