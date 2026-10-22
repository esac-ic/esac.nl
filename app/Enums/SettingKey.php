<?php

namespace App\Enums;

/**
 * The names of the rows in the settings table.
 */
enum SettingKey: string
{
    case BlockedEmailDomains = 'SETTING_BLOCKED_EMAIL_DOMAINS';
    case Killswitch = 'SETTING_KILLSWITCH';
    case ShowIntroOption = 'SETTING_SHOW_INTRO_OPTION';
    case FooterPhoneNumber = 'SETTING_FOOTER_PHONE_NR';
    case NormalMemberMailLists = 'SETTING_NORMAL_MEMBER_MAIL_LISTS';
    case ExtraordinaryMemberMailLists = 'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS';
    case ReunistMemberMailLists = 'SETTING_REUNIST_MEMBER_MAIL_LISTS';
    case HonoraryMemberMailLists = 'SETTING_HONORARY_MEMBER_MAIL_LISTS';
    case MeritMemberMailLists = 'SETTING_MERIT_MEMBER_MAIL_LISTS';
    case TrainerMemberMailLists = 'SETTING_TRAINER_MEMBER_MAIL_LISTS';
    case RelationshipMemberMailLists = 'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS';

    /**
     * The setting with the mail lists for a kind of member.
     */
    public static function mailListsFor(string $kindOfMember): ?self
    {
        return match ($kindOfMember) {
            'member' => self::NormalMemberMailLists,
            'extraordinary_member' => self::ExtraordinaryMemberMailLists,
            'reunist' => self::ReunistMemberMailLists,
            'honorary_member' => self::HonoraryMemberMailLists,
            'member_of_merit' => self::MeritMemberMailLists,
            'trainer' => self::TrainerMemberMailLists,
            'relationship' => self::RelationshipMemberMailLists,
            default => null,
        };
    }
}
