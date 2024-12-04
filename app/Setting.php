<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    //setting names
    const SETTING_BLOCKED_EMAIL_DOMAINS = 'SETTING_BLOCKED_EMAIL_DOMAINS';
    const SETTING_KILLSWITCH = 'SETTING_KILLSWITCH';
    const SETTING_SHOW_INTRO_OPTION = 'SETTING_SHOW_INTRO_OPTION';
    const SETTING_FOOTER_PHONE_NR = 'SETTING_FOOTER_PHONE_NR';
    const SETTING_CURRENT_MEMBER_MAIL_LISTS = 'SETTING_CURRENT_MEMBER_MAIL_LISTS';
    const SETTING_OLD_MEMBER_MAIL_LISTS = 'SETTING_OLD_MEMBER_MAIL_LISTS';
    const SETTING_PENDING_MEMBER_MAIL_LISTS = 'SETTING_PENDING_MEMBER_MAIL_LISTS';

    //setting types
    const TYPE_STRING = 'TYPE_STRING';
    const TYPE_BOOLEAN = 'TYPE_BOOLEAN';

    const SINGELTONNAME = 'setting';

    private $_settings = array();

    protected $fillable = [
        'name',
        'type',
        'value',
    ];

    public function initialise()
    {
        $this->_settings = array();
        foreach (Setting::all() as $setting) {
            $this->_settings[$setting->name] = $setting->value;
        }
    }

    public function getSetting($name)
    {
        if (array_key_exists($name, $this->_settings)) {
            return $this->_settings[$name];
        } else {
            Log::alert('Setting with the name: ' . $name . ' asked but not found in settings list!');
            return "";
        }
    }
}
