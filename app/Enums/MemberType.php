<?php

namespace App\Enums;

use App\Settings\SiteSettings;

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
     * @return string[]
     */
    public function mailLists(): array
    {
        return app(SiteSettings::class)->mail_lists[$this->value];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $type) => [$type->value => $type->label()])->all();
    }
}
