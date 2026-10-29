<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * The association's contact details in the footer.
 */
class ContactSettings extends Settings
{
    public ?string $phone_number;

    public string $email;

    public string $iban;

    public string $kvk_number;

    public ?string $facebook_url;

    public ?string $instagram_url;

    public ?string $youtube_url;

    public static function group(): string
    {
        return 'contact';
    }
}
