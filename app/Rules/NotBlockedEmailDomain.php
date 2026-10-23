<?php

namespace App\Rules;

use App\Enums\SettingKey;
use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects email addresses on the blocked domains from the settings.
 */
class NotBlockedEmailDomain implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $parts = explode('@', $value);
        $blockedDomains = explode(';', Setting::valueOf(SettingKey::BlockedEmailDomains));

        if (count($parts) != 2 || in_array($parts[1], $blockedDomains)) {
            $fail('Email addresses of educational institutions (student.tue.nl) are not allowed to make sure you can still receive our emails after you graduate');
        }
    }
}
