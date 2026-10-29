<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Event
 */
class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => route('events.show', $this->resource),
            'registrationUrl' => route('events.registration.create', $this->resource),
            'thumbnail' => $this->image_url,
            'startDate' => $this->start_date->format('d M'),
            'text' => $this->short_description,
            'formId' => $this->registrationForm,
            'canRegister' => $this->isRegistrationOpen(),
            // Counted and checked by the events API's query.
            'amountOfPeopleRegisterd' => $this->registration_form_id ? $this->registrations_count : 0,
            'currentUserSignedUp' => $this->registration_form_id && $this->registrations->isNotEmpty(),
        ];
    }
}
