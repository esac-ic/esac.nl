<?php

namespace App\Enums;

/**
 * The names of the rows in the settings table.
 */
enum SettingKey: string
{
    case BlockedEmailDomains = 'BLOCKED_EMAIL_DOMAINS';
    case Killswitch = 'KILLSWITCH';
    case FooterPhoneNumber = 'FOOTER_PHONE_NUMBER';
    case MemberMailLists = 'MEMBER_MAIL_LISTS';
    case ExtraordinaryMemberMailLists = 'EXTRAORDINARY_MEMBER_MAIL_LISTS';
    case AlumnusMailLists = 'ALUMNUS_MAIL_LISTS';
    case HonoraryMemberMailLists = 'HONORARY_MEMBER_MAIL_LISTS';
    case MemberOfMeritMailLists = 'MEMBER_OF_MERIT_MAIL_LISTS';
    case TrainerMailLists = 'TRAINER_MAIL_LISTS';
    case RelationshipMailLists = 'RELATIONSHIP_MAIL_LISTS';

    public function label(): string
    {
        return match ($this) {
            self::BlockedEmailDomains => 'Blocked email domains',
            self::Killswitch => 'Block complete website front-end (0 or 1)',
            self::FooterPhoneNumber => 'Phone number in footer',
            self::MemberMailLists => 'Member mail lists',
            self::ExtraordinaryMemberMailLists => 'Extraordinary member mail lists',
            self::AlumnusMailLists => 'Alumnus mail lists',
            self::HonoraryMemberMailLists => 'Honorary member mail lists',
            self::MemberOfMeritMailLists => 'Member of merit mail lists',
            self::TrainerMailLists => 'Trainer mail lists',
            self::RelationshipMailLists => 'Relationship mail lists',
        };
    }
}
