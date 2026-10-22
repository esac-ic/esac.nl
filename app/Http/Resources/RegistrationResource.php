<?php

namespace App\Http\Resources;

use App\Models\ApplicationForm\ApplicationResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A member's registration for an event, as the registrations table on their profile reads it.
 *
 * @mixin ApplicationResponse
 */
class RegistrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->agendaItem->title,
            'startDate' => $this->agendaItem->start_date->format('d-m-Y H:i'),
            'actions' => '
                    <a href="'.route('beheer.agenda-items.registrations.show', [$this->agenda_id, $this->user_id]).'"><span title="'.'Show entered information'.'" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" id="delete_button" data-url="'.route('beheer.agenda-items.registrations.destroy', [$this->agenda_id, $this->resource]).'"><span  class="ion-trash-a"></span></a>
                ',
        ];
    }
}
