<?php

namespace App\Exports;

use App\AgendaItem;
use App\repositories\InschrijvenRepository;
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
     * @var InschrijvenRepository
     */
    private $agendaRegistrationRepository;

    /**
     * @var AgendaItem
     */
    private $agendaItem;

    /**
     * AgendaRegistrationExport constructor.
     * @param InschrijvenRepository $agendaRegistrationRepository
     * @param int $agendaId
     */
    public function __construct(InschrijvenRepository $agendaRegistrationRepository, AgendaItem $agendaItem)
    {
        $this->agendaRegistrationRepository = $agendaRegistrationRepository;
        $this->agendaItem = $agendaItem;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->agendaRegistrationRepository->getExportData($this->agendaItem->id);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return trans('forms.Inschrijvingen');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $defaultValues = [
            trans('user.firstname'),
            trans('user.preposition'),
            trans('user.lastname'),
            trans('user.street'),
            trans('user.housenumber'),
            trans('user.city'),
            trans('user.email'),
            trans('user.phonenumber'),
        ];

        $formQuestions = [];
        $rows = $this->agendaItem->getApplicationForm->getActiveApplicationFormRows;
        foreach ($rows as $row) {
            $formQuestions[] = $row->applicationFormRowName->text();
        }

        return array_merge($defaultValues, $formQuestions);

    }
}
