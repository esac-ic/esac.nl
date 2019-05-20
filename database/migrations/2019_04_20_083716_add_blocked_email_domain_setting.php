<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlockedEmailDomainSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = new \App\Setting();
        $setting->name = \App\Setting::SETTING_BLOCKED_EMAIL_DOMAINS;
        $setting->type = \App\Setting::TYPE_STRING;
        $setting->value = 'student.tue.nl;.student.fontys.nl';
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
