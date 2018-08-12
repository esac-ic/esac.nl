<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AgendaItem extends Model
{
    protected $fillable = [
        'title',
        'text',
        'shortDescription',
        'subscription_endDate',
        'startDate',
        'endDate',
        'image_url',
        'category',
    ];

    public function getApplicationForm(){
        return $this->hasOne('App\ApplicationForm','id',"application_form_id");
    }

    public function agendaItemText(){
        return $this->hasOne('App\Text', 'id', 'text');
    }

    public function agendaItemTitle()
    {
        return $this->hasOne('App\Text', 'id', 'title');
    }
    public function agendaItemShortDescription()
    {
        return $this->hasOne('App\Text', 'id', 'shortDescription');
    }

    public function agendaItemCategory()
    {
        return $this->hasOne('App\AgendaItemCategorie', 'id', 'category')->withTrashed();
    }

    public function getCreatedBy()
    {
        return $this->hasOne('App\User', 'id', 'createdBy');
    }

    public function getImageUrl(){
        if($this->image_url != ""){
            return \Storage::disk('public')->url($this->image_url);
        } else {
            return "/img/default_agenda_item_cover.jpg";
        }
    }

    public function getApplicationFormResponses(){
        return $this->hasMany('App\ApplicationResponse','agenda_id');
    }

    public function canRegister(){
        if($this->subscription_endDate < Carbon::now()){
            return false;
        } else {
            return true;
        }
    }
}
