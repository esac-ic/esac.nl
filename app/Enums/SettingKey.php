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
    case ExtraordinaryParticipantMailLists = 'EXTRAORDINARY_PARTICIPANT_MAIL_LISTS';
    case ReunistMailLists = 'REUNIST_MAIL_LISTS';
    case HonoraryMemberMailLists = 'HONORARY_MEMBER_MAIL_LISTS';
    case MemberOfServiceMailLists = 'MEMBER_OF_SERVICE_MAIL_LISTS';
    case TrainerMailLists = 'TRAINER_MAIL_LISTS';
    case RelationshipMailLists = 'RELATIONSHIP_MAIL_LISTS';

}
