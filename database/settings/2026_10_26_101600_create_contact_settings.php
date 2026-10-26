<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Moves the footer's contact details into settings: the phone number from the site settings, the rest from the footer.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->rename('site.footer_phone_number', 'contact.phone_number');
        $this->migrator->add('contact.email', 'bestuur@esac.nl');
        $this->migrator->add('contact.iban', 'NL75 RABO 0117 0524 85');
        $this->migrator->add('contact.kvk_number', '40236076');
        $this->migrator->add('contact.facebook_url', 'https://www.facebook.com/esac040');
        $this->migrator->add('contact.instagram_url', 'https://www.instagram.com/esac.nl/');
        $this->migrator->add('contact.youtube_url', 'https://www.youtube.com/channel/UCn6jb9BA10f-rbvinYCYlxA/');
    }

    public function down(): void
    {
        $this->migrator->rename('contact.phone_number', 'site.footer_phone_number');
        foreach (['email', 'iban', 'kvk_number', 'facebook_url', 'instagram_url', 'youtube_url'] as $name) {
            $this->migrator->delete("contact.$name");
        }
    }
};
