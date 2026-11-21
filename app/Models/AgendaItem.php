<?php

namespace App\Models;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationResponse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'shortDescription',
        'subscription_endDate',
        'startDate',
        'endDate',
        'image_url',
        'category',
        'climbing_activity',
        'hidden',
    ];

    protected $casts = [
        'climbing_activity' => 'boolean',
    ];

    public function getApplicationForm(): HasOne
    {
        return $this->hasOne(ApplicationForm::class, 'id', "application_form_id");
    }

    public function agendaItemCategory()
    {
        return $this->hasOne(AgendaItemCategory::class, 'id', 'category')->withTrashed();
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, 'id', 'createdBy');
    }

    public function getImageUrl()
    {
        if ($this->image_url != "") {
            return Storage::disk('public')->url($this->image_url);
        } else {
            return "/img/default_agenda_item_cover.jpg";
        }
    }

    public function getApplicationFormResponses()
    {
        return $this->hasMany(ApplicationResponse::class, 'agenda_id');
    }

    public function canRegister()
    {
        if ($this->subscription_endDate < Carbon::now()) {
            return false;
        } else {
            return true;
        }
    }
}
