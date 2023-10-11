<?php

namespace App\Exports;

use App\AgendaItem;
use App\Services\AgendaApplicationFormService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Class AgendaRegistrationExport
 * @package App\Exports
 */
class AgendaRegistrationExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    /**
     * @var AgendaApplicationFormService
     */
    private $agendaApplicationFormService;

    /**
     * @var AgendaItem
     */
    private $agendaItem;

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
    public function collection()
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
            'Email address',
            'Phone number',
        ];

        $formQuestions = [];
        $rows = $this->agendaItem->getApplicationForm->applicationFormRows;
        foreach ($rows as $row) {
            $formQuestions[] = $row->name;
        }

        return array_merge($defaultValues, $formQuestions);

    }
}
