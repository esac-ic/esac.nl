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

}
