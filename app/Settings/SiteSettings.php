<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    /** Whether the public website is closed. */
    public bool $killswitch;

    public ?string $footer_phone_number;

    /** @var string[] The email domains that can't sign up. */
    public array $blocked_email_domains;

    /** The mail list ids by member type value. */
    public array $mail_lists;

    public static function group(): string
    {
        return 'site';
    }
}
