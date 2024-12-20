<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //standard settings from the production site
        $setting = new Setting(['name' => Setting::SETTING_BLOCKED_EMAIL_DOMAINS, 'type' => Setting::TYPE_STRING, 'value' => 'student.tue.nl;student.fontys.nl;tue.nl']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_KILLSWITCH, 'type' => Setting::TYPE_BOOLEAN, 'value' => false]);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_SHOW_INTRO_OPTION, 'type' => Setting::TYPE_BOOLEAN, 'value' => false]);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_FOOTER_PHONE_NR, 'type' => Setting::TYPE_STRING, 'value' => '06-12345678']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_CURRENT_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'alle-leden;nieuwsbrief']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_OLD_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'reunisten']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_PENDING_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => '']);
        $setting->save();
    }
}
