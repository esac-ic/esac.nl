<?php

namespace App\Http\Resources;

use App\Models\AgendaItem;
use App\Services\AgendaApplicationFormService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * An event as the agenda page's JavaScript reads it.
 *
 * @mixin AgendaItem
 */
class AgendaItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $registeredUserIds = $this->application_form_id
            ? app(AgendaApplicationFormService::class)->getRegisteredUserIds($this->resource)
            : [];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'thumbnail' => $this->getImageUrl(),
            'startDate' => $this->start_date->format('d M'),
            'endDate' => $this->end_date->toDateTimeString(),
            'full_startDate' => $this->start_date->toDateTimeString(),
            'category' => $this->agendaItemCategory->name,
            'text' => $this->short_description,
            'formId' => $this->getApplicationForm,
            'canRegister' => $this->canRegister(),
            'application_form_id' => $this->application_form_id,
            'amountOfPeopleRegisterd' => count($registeredUserIds),
            'currentUserSignedUp' => $request->user() ? in_array($request->user()->id, $registeredUserIds) : false,
        ];
    }
}
