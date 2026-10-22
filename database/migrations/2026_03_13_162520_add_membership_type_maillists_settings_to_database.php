<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::insert([
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
        Setting::where('name', 'SETTING_NORMAL_MEMBER_MAIL_LISTS')->delete();
        Setting::where('name', 'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS')->delete();
        Setting::where('name', 'SETTING_REUNIST_MEMBER_MAIL_LISTS')->delete();
        Setting::where('name', 'SETTING_HONORARY_MEMBER_MAIL_LISTS')->delete();
        Setting::where('name', 'SETTING_MERIT_MEMBER_MAIL_LISTS')->delete();
        Setting::where('name', 'SETTING_TRAINER_MEMBER_MAIL_LISTS')->delete();
        Setting::where('name', 'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS')->delete();
    }
};
