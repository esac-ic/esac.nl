<?php

namespace App\Http\Resources;

use App\Models\AgendaItem;
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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'thumbnail' => $this->getImageUrl(),
            'startDate' => $this->start_date->format('d M'),
            'endDate' => $this->end_date->toDateTimeString(),
            'full_startDate' => $this->start_date->toDateTimeString(),
            'category' => $this->agendaItemCategory->name,
            'text' => $this->short_description,
            'formId' => $this->applicationForm,
            'canRegister' => $this->canRegister(),
            'application_form_id' => $this->application_form_id,
            // Counted and checked by the agenda API's query.
            'amountOfPeopleRegisterd' => $this->application_form_id ? $this->registrations_count : 0,
            'currentUserSignedUp' => $this->application_form_id && $this->registrations->isNotEmpty(),
        ];
    }
}
