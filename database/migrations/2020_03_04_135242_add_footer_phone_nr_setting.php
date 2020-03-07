<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;

class AddFooterPhoneNrSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = new Setting();
        $setting->name = Setting::SETTING_FOOTER_PHONE_NR;
        $setting->type = Setting::TYPE_STRING;
        $setting->value = '+31 6 83994645'; // Leon Cavé (President 44th board)
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('name', Setting::SETTING_FOOTER_PHONE_NR)->delete();
    }
}
