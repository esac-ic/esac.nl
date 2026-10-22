<?php

namespace App\Http\Resources;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A member's registration for an event, as the registrations table on their profile reads it.
 *
 * @mixin Registration
 */
class RegistrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->event->title,
            'startDate' => $this->event->start_date->format('d-m-Y H:i'),
            'actions' => '
                    <a href="'.route('admin.events.registrations.show', [$this->event_id, $this->resource]).'"><span title="'.'Show entered information'.'" class="ion-eye" aria-hidden="true"></span></a>
                    <a href="#" class="js-delete-registration" data-destroy-url="'.route('admin.events.registrations.destroy', [$this->event_id, $this->resource]).'"><span  class="ion-trash-a"></span></a>
                ',
        ];
    }
}
