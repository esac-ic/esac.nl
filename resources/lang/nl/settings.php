<?php

use App\Setting;

return [
    'setting' => 'Instelling',
    'settings' => 'Instellingen',
    'edit' => 'Pas instellingen aan',
    'name' => 'Naam',
    'value' => 'Waarde',
    'flashUpdateSetting' => 'Instellingen bijgewerkt',

    Setting::SETTING_BLOCKED_EMAIL_DOMAINS => 'Geblokkeerde email domains',
    Setting::SETTING_KILLSWITCH => 'Blokkeer complete website front-end (0 of 1)',
    Setting::SETTING_SHOW_INTRO_OPTION => 'Toon intro pakket opties (0 of 1)',
    Setting::SETTING_FOOTER_PHONE_NR => 'Telefoonnummer in de footer',
    Setting::SETTING_DEFAULT_MAILING_LISTS_FOR_NEW_USERS => 'Standaard mail lijsten voor een nieuwe gebruiker. Gescheiden door ;'
];
