<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->insert([
            ['name' => 'SETTING_NORMAL_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => 'alle-leden.esac.nl;nieuwsbrief.esac.nl'],
            ['name' => 'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => 'reunist.esac.nl;alle-leden.esac.nl;nieuwsbrief.esac.nl'],
            ['name' => 'SETTING_REUNIST_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => 'reunist.esac.nl'],
            ['name' => 'SETTING_HONORARY_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => ''],
            ['name' => 'SETTING_MERIT_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => ''],
            ['name' => 'SETTING_TRAINER_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => 'alle-leden.esac.nl;nieuwsbrief.esac.nl'],
            ['name' => 'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS', 'type' => 'TYPE_STRING', 'value' => ''],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->where('name', 'SETTING_NORMAL_MEMBER_MAIL_LISTS')->delete();
        DB::table('settings')->where('name', 'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS')->delete();
        DB::table('settings')->where('name', 'SETTING_REUNIST_MEMBER_MAIL_LISTS')->delete();
        DB::table('settings')->where('name', 'SETTING_HONORARY_MEMBER_MAIL_LISTS')->delete();
        DB::table('settings')->where('name', 'SETTING_MERIT_MEMBER_MAIL_LISTS')->delete();
        DB::table('settings')->where('name', 'SETTING_TRAINER_MEMBER_MAIL_LISTS')->delete();
        DB::table('settings')->where('name', 'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS')->delete();
    }
};
