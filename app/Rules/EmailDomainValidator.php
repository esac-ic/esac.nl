<?php

namespace App\Rules;

use App\Enums\SettingKey;
use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects email addresses on the blocked domains from the settings.
 */
class EmailDomainValidator implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $splitEmail = explode('@', $value);
        $bannedDomains = explode(';', Setting::valueOf(SettingKey::BlockedEmailDomains));

        if (count($splitEmail) != 2 || in_array($splitEmail[1], $bannedDomains)) {
            $fail('Email addresses of educational institutions (student.tue.nl) are not allowed to make sure you can still receive our emails after you graduate');
        }
    }
}
