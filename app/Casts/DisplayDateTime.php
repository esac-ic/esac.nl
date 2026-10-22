<?php

namespace App\Casts;

use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

/**
 * A datetime stored in UTC, read in the display timezone, with strings written as display timezone wall time.
 *
 * @implements CastsAttributes<Carbon, DateTimeInterface|string>
 */
class DisplayDateTime implements CastsAttributes
{
    public bool $withoutObjectCaching = true;

    public function get(Model $model, string $key, mixed $value, array $attributes): ?Carbon
    {
        return $value === null ? null : Date::parse($value, 'UTC')->setTimezone(config('app.display_timezone'));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $date = $value instanceof DateTimeInterface ? Date::instance($value) : Date::parse($value, config('app.display_timezone'));

        return $date->utc()->format($model->getDateFormat());
    }
}
