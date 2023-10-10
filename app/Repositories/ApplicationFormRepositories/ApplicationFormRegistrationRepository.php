<?php

namespace App\Repositories\ApplicationFormRepositories;

use App\AgendaItem;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ApplicationFormRegistrationRepository
 * @package App\Repositories\ApplicationFormRepositories
 */
class ApplicationFormRegistrationRepository
{
    /**
     * @param array $data
     * @param AgendaItem $agendaItem
     * @param int $userId
     */
    public function storeRegistration(array $data, AgendaItem $agendaItem, int $userId): void
    {
        $applicationResponse = new ApplicationResponse();

        $applicationFormId = $agendaItem->application_form_id;

        $applicationResponse->agenda_id = $agendaItem->id;
        $applicationResponse->inschrijf_form_id = $applicationFormId;
        $applicationResponse->user_id = $userId;
        $applicationResponse->save();
        $applicationResponseId = $applicationResponse->id;

        //set the responserows
        foreach ($data as $key => $value) {
            $applicationResponseRow = new ApplicationResponseRow();
            $applicationResponseRow->application_response_id = $applicationResponseId;
            $applicationResponseRow->application_form_row_id = $key;
            $applicationResponseRow->value = gettype($value) === 'array' ? implode(',', $value) : $value;
            $applicationResponseRow->save();
        }
    }

    /**
     * @param $agendaId
     * @param $userId
     * @return Collection
     */
    public function getApplicationInformation($agendaId, $userId): Collection
    {
        $applicationResponse = ApplicationResponse::query()
            ->where('agenda_id', $agendaId)
            ->where('user_id', $userId)
            ->select('id')
            ->firstOrFail();

        return ApplicationResponseRow::where('application_response_id', $applicationResponse->id)->get();
    }
}
