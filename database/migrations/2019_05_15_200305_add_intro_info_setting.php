<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntroInfoSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = new \App\Setting();
        $setting->name = \App\Setting::SETTING_SHOW_INTRO_OPTION;
        $setting->type = \App\Setting::TYPE_BOOLEAN;
        $setting->value = 0;
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
