<?php

namespace App\Enums;

/**
 * The names of the rows in the settings table.
 */
enum SettingKey: string
{
    case BlockedEmailDomains = 'blocked_email_domains';
    case Killswitch = 'killswitch';
    case FooterPhoneNumber = 'footer_phone_number';
    case MemberMailLists = 'member_mail_lists';
    case ExtraordinaryParticipantMailLists = 'extraordinary_participant_mail_lists';
    case ReunistMailLists = 'reunist_mail_lists';
    case HonoraryMemberMailLists = 'honorary_member_mail_lists';
    case MemberOfServiceMailLists = 'member_of_service_mail_lists';
    case TrainerMailLists = 'trainer_mail_lists';
    case RelationshipMailLists = 'relationship_mail_lists';

}
