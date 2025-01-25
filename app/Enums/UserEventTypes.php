<?php

namespace App\Enums;

/**
 * Enum of all user event types that are stored in the user event log.
 */
enum UserEventTypes:string {
    case PendingUserApproved = "PENDING_USER_APPROVED";
    case PendingUserRemoved = "PENDING_USER_REMOVED";
    case MemberBecameOldMember = "MEMBER_BECAME_OLD_MEMBER";
    case OldMemberBecameMember = "OLD_MEMBER_BECAME_MEMBER";
    case MemberTypeChanged = "MEMBER_TYPE_CHANGED"; //eg lid -> reunist
    
    /**
     * Returns an array of the values of the cases.
     * 
     * @return array of strings
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}