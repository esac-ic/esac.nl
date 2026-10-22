<?php

namespace App\Models;

use App\Enums\SettingKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * The settings rows are added by migrations, not by a seeder or the management pages.
 */
#[Fillable([
    'name',
    'value',
])]
class Setting extends Model
{
    public static function valueOf(SettingKey $key): string
    {
        // All settings are read with one query per request (or queued job).
        return (string) once(fn () => static::query()->pluck('value', 'name'))[$key->value];
    }

    public static function isEnabled(SettingKey $key): bool
    {
        return (bool) static::valueOf($key);
    }
}
