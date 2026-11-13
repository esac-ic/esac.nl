<?php

namespace App\Enums;

enum ReimbursementStatus: string 
{
    case DRAFT = "Draft";
    case PENDING = "Pending";
    case ACCEPTED = "Accepted";
    case REJECTED = "Rejected";

    public static function labels(): array
    {   
        return array_column(ReimbursementStatus::cases(), 'value', 'value');
    }
}