<?php

namespace App\Enums;

/**
 * The roles that give access to the management pages; AppServiceProvider maps them to abilities.
 */
enum AdminRole: string
{
    case Administrator = 'administrator';
    case ContentAdministrator = 'content_administrator';
    case EventAdministrator = 'event_administrator';
    case CertificateAdministrator = 'certificate_administrator';

    public function label(): string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::ContentAdministrator => 'Content administrator',
            self::EventAdministrator => 'Event administrator',
            self::CertificateAdministrator => 'Certificate administrator',
        };
    }
}
