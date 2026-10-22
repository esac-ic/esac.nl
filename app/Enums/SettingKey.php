<?php

namespace App\Enums;

/**
 * The names of the rows in the settings table.
 */
enum SettingKey: string
{
    case BlockedEmailDomains = 'SETTING_BLOCKED_EMAIL_DOMAINS';
    case Killswitch = 'SETTING_KILLSWITCH';
    case FooterPhoneNumber = 'SETTING_FOOTER_PHONE_NR';
    case NormalMemberMailLists = 'SETTING_NORMAL_MEMBER_MAIL_LISTS';
    case ExtraordinaryMemberMailLists = 'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS';
    case ReunistMemberMailLists = 'SETTING_REUNIST_MEMBER_MAIL_LISTS';
    case HonoraryMemberMailLists = 'SETTING_HONORARY_MEMBER_MAIL_LISTS';
    case MeritMemberMailLists = 'SETTING_MERIT_MEMBER_MAIL_LISTS';
    case TrainerMemberMailLists = 'SETTING_TRAINER_MEMBER_MAIL_LISTS';
    case RelationshipMemberMailLists = 'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS';

    public function label(): string
    {
        return match ($this) {
            self::BlockedEmailDomains => 'Blocked email domains',
            self::Killswitch => 'Block complete website front-end (0 or 1)',
            self::FooterPhoneNumber => 'Phone number in footer',
            self::NormalMemberMailLists => 'Normal member mail lists',
            self::ExtraordinaryMemberMailLists => 'Extraordinary participant mail lists',
            self::ReunistMemberMailLists => 'Reunist mail lists',
            self::HonoraryMemberMailLists => 'Honorary member mail lists',
            self::MeritMemberMailLists => 'Member of service mail lists',
            self::TrainerMemberMailLists => 'Trainer mail lists',
            self::RelationshipMemberMailLists => 'Relationship member mail lists',
        };
    }
}
