<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //setting names
    const SETTING_BLOCKED_EMAIL_DOMAINS = 'SETTING_BLOCKED_EMAIL_DOMAINS';

    //setting types
    const TYPE_STRING = 'TYPE_STRING';

    const SINGELTONNAME = 'setting';

    private $_settings = array();

    protected $fillable = [
        'name',
        'type',
        'value'
    ];

    public function initialise(){
        $this->_settings = array();
        foreach (Setting::all() as $setting){
            $this->_settings[$setting->name] = $setting->value;
        }
    }

    public function getSetting($name){
        if(array_key_exists($name,$this->_settings)){
            return $this->_settings[$name];
        } else {
            \Log::alert('Setting with the name: ' . $name . ' asked but not found in settings list!');
            return "";
        }
    }
}
