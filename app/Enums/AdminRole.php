<?php

namespace App\Enums;

/**
 * The roles that grant access to the management pages, by their id in the roles table.
 */
enum AdminRole: int
{
    case Administrator = 1;
    case ContentAdministrator = 2;
    case ActivityAdministrator = 3;
    case CertificateAdministrator = 4;
}
