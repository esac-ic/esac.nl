<?php

namespace App\Enums;

use App\Models\Setting;

/**
 * The kind of participant of the association (statutes and HR), which decides a member's mail lists.
 */
enum MemberType: string
{
    case Member = 'member';
    case ExtraordinaryParticipant = 'extraordinary_participant';
    case Reunist = 'reunist';
    case HonoraryMember = 'honorary_member';
    case MemberOfService = 'member_of_service';
    case Trainer = 'trainer';
    case Relationship = 'relationship';

    public function label(): string
    {
        return match ($this) {
            self::Member => 'Member',
            self::ExtraordinaryParticipant => 'Extraordinary participant',
            self::Reunist => 'Reunist',
            self::HonoraryMember => 'Honorary member',
            self::MemberOfService => 'Member of service',
            self::Trainer => 'Trainer',
            self::Relationship => 'Relationship',
        };
    }

    /**
     * The setting that lists this member type's mail lists.
     */
    public function mailListSetting(): SettingKey
    {
        return match ($this) {
            self::Member => SettingKey::MemberMailLists,
            self::ExtraordinaryParticipant => SettingKey::ExtraordinaryParticipantMailLists,
            self::Reunist => SettingKey::ReunistMailLists,
            self::HonoraryMember => SettingKey::HonoraryMemberMailLists,
            self::MemberOfService => SettingKey::MemberOfServiceMailLists,
            self::Trainer => SettingKey::TrainerMailLists,
            self::Relationship => SettingKey::RelationshipMailLists,
        };
    }

    /**
     * @return string[]
     */
    public function mailLists(): array
    {
        $mailLists = trim(Setting::valueOf($this->mailListSetting()));

        return $mailLists === '' ? [] : explode(';', $mailLists);
    }

    /**
     * The labels by value, for a select.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $type) => [$type->value => $type->label()])->all();
    }
}
