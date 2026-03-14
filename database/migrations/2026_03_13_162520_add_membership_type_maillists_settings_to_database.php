<?php

use App\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $setting = new Setting(['name' => Setting::SETTING_NORMAL_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'alle-leden.esac.nl;nieuwsbrief.esac.nl']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'reunist.esac.nl;alle-leden.esac.nl;nieuwsbrief.esac.nl']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_REUNIST_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'reunist.esac.nl']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_HONORARY_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => '']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_MERIT_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => '']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_TRAINER_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => 'alle-leden.esac.nl;nieuwsbrief.esac.nl']);
        $setting->save();
        $setting = new Setting(['name' => Setting::SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS, 'type' => Setting::TYPE_STRING, 'value' => '']);
        $setting->save();
    }
};
