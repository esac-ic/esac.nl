<?php

namespace App\Rules;

use App\Setting;
use Illuminate\Contracts\Validation\Rule;

class EmailDomainValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $splitEmail = explode("@",$value);

        if(count($splitEmail) != 2) {
            return false;
        }

        $domain = $splitEmail[1];
        $bannedDomains = explode(";", app(Setting::SINGELTONNAME)->getSetting(Setting::SETTING_BLOCKED_EMAIL_DOMAINS));

        return !in_array($domain, $bannedDomains);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.bannedEmailDomain');
    }
}
