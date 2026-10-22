<?php

namespace App\Enums;

/**
 * The roles that grant access to the management pages, by their id in the rols table.
 */
enum Role: int
{
    case Administrator = 1;
    case ContentAdministrator = 2;
    case ActivityAdministrator = 3;
    case CertificateAdministrator = 4;
}
