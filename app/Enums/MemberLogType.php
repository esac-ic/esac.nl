<?php

namespace App\Enums;

/**
 * The kinds of member log entry.
 */
enum MemberLogType: string
{
    case MemberSignedUp = 'member_signed_up';
    case SignUpApproved = 'sign_up_approved';
    case SignUpRejected = 'sign_up_rejected';
    case MemberTypeChanged = 'member_type_changed';
    case MembershipEnded = 'membership_ended';
    case MembershipRestored = 'membership_restored';

    public function label(): string
    {
        return match ($this) {
            self::MemberSignedUp => 'Signed up',
            self::SignUpApproved => 'Sign-up approved',
            self::SignUpRejected => 'Sign-up rejected',
            self::MemberTypeChanged => 'Member type changed',
            self::MembershipEnded => 'Membership ended',
            self::MembershipRestored => 'Membership restored',
        };
    }
}
