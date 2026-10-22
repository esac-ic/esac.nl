<?php

namespace App\Models;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'text',
    'short_description',
    'subscription_end_date',
    'start_date',
    'end_date',
    'image_url',
    'category',
    'climbing_activity',
])]
class AgendaItem extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'climbing_activity' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'subscription_end_date' => 'datetime',
        ];
    }

    /**
     * Events that start today or later, the first one first.
     */
    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->whereDate('start_date', '>=', Carbon::now())->orderBy('start_date', 'ASC');
    }

    /**
     * Sets the dates, registration form and climbing flag from the event form's data.
     */
    public function fillFromForm(array $data): static
    {
        $starttime = new \DateTime($data['start_date']);
        $endtime = new \DateTime($data['end_date']);
        $subscription_endDate = new \DateTime($data['subscription_end_date']);
        $this->start_date = Carbon::createFromFormat('d-m-Y H:i', $starttime->format('d-m-Y') . ' ' . $starttime->format('H:i'));
        $this->end_date = Carbon::createFromFormat('d-m-Y H:i', $endtime->format('d-m-Y') . ' ' . $endtime->format('H:i'));

        if ($data['applicationForm'] != 0) {
            $this->subscription_end_date = Carbon::createFromFormat('d-m-Y H:i', $subscription_endDate->format('d-m-Y') . ' ' . $subscription_endDate->format('H:i'));
            $this->application_form_id = $data['applicationForm'];
        } else {
            $this->subscription_end_date = null;
            $this->application_form_id = null;
        }

        $this->climbing_activity = array_key_exists('climbing_activity', $data);

        return $this;
    }

    /**
     * Saves a copy of the event, with a copy of its image, created by the given user.
     */
    public function copyFor(User $creator): AgendaItem
    {
        $newAgendaItem = $this->replicate();
        $newAgendaItem->created_by = $creator->id;
        $newAgendaItem->save();

        if ($this->image_url !== null && $this->image_url !== "") {
            $oldPath = $this->image_url;
            $newPath = str_replace($this->id, $newAgendaItem->id, $oldPath);
            Storage::disk('public')->copy($oldPath, $newPath);
            $newAgendaItem->image_url = $newPath;
            $newAgendaItem->save();
        }

        return $newAgendaItem;
    }

    /**
     * Registers the user for the event with their answers, keyed by the form row id.
     */
    public function register(int $userId, array $answers): void
    {
        $applicationResponse = new ApplicationResponse();
        $applicationResponse->agenda_id = $this->id;
        $applicationResponse->inschrijf_form_id = $this->application_form_id;
        $applicationResponse->user_id = $userId;
        $applicationResponse->save();

        foreach ($answers as $key => $value) {
            $applicationResponseRow = new ApplicationResponseRow();
            $applicationResponseRow->application_response_id = $applicationResponse->id;
            $applicationResponseRow->application_form_row_id = $key;
            $applicationResponseRow->value = gettype($value) === 'array' ? implode(',', $value) : $value ?? '';
            $applicationResponseRow->save();
        }
    }

    public function getApplicationForm(): HasOne
    {
        return $this->hasOne(ApplicationForm::class, 'id', "application_form_id");
    }

    public function agendaItemCategory(): HasOne
    {
        return $this->hasOne(AgendaItemCategory::class, 'id', 'category')->withTrashed();
    }

    public function getCreatedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getImageUrl()
    {
        if ($this->image_url != "") {
            return Storage::disk('public')->url($this->image_url);
        } else {
            return "/img/default_agenda_item_cover.jpg";
        }
    }

    public function getApplicationFormResponses(): HasMany
    {
        return $this->hasMany(ApplicationResponse::class, 'agenda_id');
    }

    public function canRegister(): bool
    {
        return ! ($this->subscription_end_date < Carbon::now());
    }
}
