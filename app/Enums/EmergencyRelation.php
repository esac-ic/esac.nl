<?php

namespace App\Enums;

enum EmergencyRelation: string 
{
    case PARENT = "Parent";
    case SIBLING = "Sibling";
    case FRIEND = "Friend";
    case PARTNER = "Partner";
    case OTHER_FAMILY = "Other Family";
    case OTHER = "Other";

    public static function labels(): array
    {   
        return array_column(EmergencyRelation::cases(), 'value', 'value');
    }
}