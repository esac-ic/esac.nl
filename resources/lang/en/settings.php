<?php

use App\Setting;

return [
    'setting' => 'Settings',
    'settings' => 'Setting',
    'edit' => 'Edit settings',
    'name' => 'Name',
    'value' => 'Value',
    'flashUpdateSetting' => 'Settings updated',

    Setting::SETTING_BLOCKED_EMAIL_DOMAINS => 'Blocked email domains',
    Setting::SETTING_KILLSWITCH => 'Block complete website front-end (0 or 1)',
    Setting::SETTING_SHOW_INTRO_OPTION => 'Show intro package options (0 or 1)',
    Setting::SETTING_FOOTER_PHONE_NR => 'Phone number in footer',
    Setting::SETTING_DEFAULT_MAILING_LISTS_FOR_NEW_USERS => 'Default mailing lists for new users'
];
