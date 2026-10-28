<?php

namespace App\Models;

use App\Enums\SettingKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * The settings rows are added by migrations, not by a seeder or the management pages.
 */
#[Fillable([
    'name',
    'type',
    'value',
])]
class Setting extends Model
{
    const TYPE_STRING = 'TYPE_STRING';

    const TYPE_BOOLEAN = 'TYPE_BOOLEAN';

    public static function valueOf(SettingKey $key): string
    {
        $setting = static::firstWhere('name', $key->value);

        if ($setting === null) {
            Log::alert('Setting with the name: '.$key->value.' asked but not found in settings list!');

            return '';
        }

        return (string) $setting->value;
    }

    public static function isEnabled(SettingKey $key): bool
    {
        return (bool) static::valueOf($key);
    }
}
