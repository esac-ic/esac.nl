<?php

namespace App\Enums;

use App\Models\Setting;

enum KindOfMember: string
{
    case Member = 'member';
    case ExtraordinaryMember = 'extraordinary_member';
    case Reunist = 'reunist';
    case HonoraryMember = 'honorary_member';
    case MemberOfMerit = 'member_of_merit';
    case Trainer = 'trainer';
    case Relationship = 'relationship';

    public function label(): string
    {
        return match ($this) {
            self::Member => 'Member',
            self::ExtraordinaryMember => 'Extraordinary participant',
            self::Reunist => 'Reunist',
            self::HonoraryMember => 'Honorary member',
            self::MemberOfMerit => 'Member of service',
            self::Trainer => 'Trainer',
            self::Relationship => 'Relationship',
        };
    }

    /**
     * The setting that lists this kind's mail lists.
     */
    public function mailListSetting(): SettingKey
    {
        return match ($this) {
            self::Member => SettingKey::NormalMemberMailLists,
            self::ExtraordinaryMember => SettingKey::ExtraordinaryMemberMailLists,
            self::Reunist => SettingKey::ReunistMemberMailLists,
            self::HonoraryMember => SettingKey::HonoraryMemberMailLists,
            self::MemberOfMerit => SettingKey::MeritMemberMailLists,
            self::Trainer => SettingKey::TrainerMemberMailLists,
            self::Relationship => SettingKey::RelationshipMemberMailLists,
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
        return collect(self::cases())->mapWithKeys(fn (self $kind) => [$kind->value => $kind->label()])->all();
    }
}
