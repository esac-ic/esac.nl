<?php

use App\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingDefaultMailingLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = new Setting();
        $setting->name = Setting::SETTING_DEFAULT_MAILING_LISTS_FOR_NEW_USERS;
        $setting->type = Setting::TYPE_STRING;
        $setting->value = '';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('name', Setting::SETTING_DEFAULT_MAILING_LISTS_FOR_NEW_USERS)->delete();
    }
}
