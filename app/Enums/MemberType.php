<?php

namespace App\Enums;

use App\Models\Setting;

/**
 * The type of membership, which decides a member's mail lists.
 */
enum MemberType: string
{
    case Member = 'member';
    case ExtraordinaryMember = 'extraordinary_member';
    case Alumnus = 'alumnus';
    case HonoraryMember = 'honorary_member';
    case MemberOfMerit = 'member_of_merit';
    case Trainer = 'trainer';
    case Relationship = 'relationship';

    public function label(): string
    {
        return match ($this) {
            self::Member => 'Member',
            self::ExtraordinaryMember => 'Extraordinary member',
            self::Alumnus => 'Alumnus',
            self::HonoraryMember => 'Honorary member',
            self::MemberOfMerit => 'Member of merit',
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
            self::ExtraordinaryMember => SettingKey::ExtraordinaryMemberMailLists,
            self::Alumnus => SettingKey::AlumnusMailLists,
            self::HonoraryMember => SettingKey::HonoraryMemberMailLists,
            self::MemberOfMerit => SettingKey::MemberOfMeritMailLists,
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
