<?php

namespace App\Enums;

enum ReimbursementType: string 
{
    case DRIVER = "Driver";
    case ACTIVITY = "Activity";
    case OTHER = "Other";

    public static function labels(): array
    {   
        return array_column(ReimbursementType::cases(), 'value', 'value');
    }
}