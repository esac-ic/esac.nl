@props(['value', 'format' => 'd-m-Y H:i'])
{{ $value?->tz(config('app.display_timezone'))->format($format) }}
