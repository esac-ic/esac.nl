<?php

use App\Enums\SettingKey;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::insert([
            ['name' => SettingKey::NormalMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => 'alle-leden.esac.nl;nieuwsbrief.esac.nl'],
            ['name' => SettingKey::ExtraordinaryMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => 'reunist.esac.nl;alle-leden.esac.nl;nieuwsbrief.esac.nl'],
            ['name' => SettingKey::ReunistMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => 'reunist.esac.nl'],
            ['name' => SettingKey::HonoraryMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => ''],
            ['name' => SettingKey::MeritMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => ''],
            ['name' => SettingKey::TrainerMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => 'alle-leden.esac.nl;nieuwsbrief.esac.nl'],
            ['name' => SettingKey::RelationshipMemberMailLists->value, 'type' => 'TYPE_STRING', 'value' => ''],
        ]);
    }

    public function down(): void
    {
        Setting::where('name', SettingKey::NormalMemberMailLists->value)->delete();
        Setting::where('name', SettingKey::ExtraordinaryMemberMailLists->value)->delete();
        Setting::where('name', SettingKey::ReunistMemberMailLists->value)->delete();
        Setting::where('name', SettingKey::HonoraryMemberMailLists->value)->delete();
        Setting::where('name', SettingKey::MeritMemberMailLists->value)->delete();
        Setting::where('name', SettingKey::TrainerMemberMailLists->value)->delete();
        Setting::where('name', SettingKey::RelationshipMemberMailLists->value)->delete();
    }
};
