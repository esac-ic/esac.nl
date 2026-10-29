<?php

namespace Tests\Concerns;

use App\Enums\MemberType;
use App\Settings\SiteSettings;

/**
 * Gives every member type its own mail list in the settings.
 */
trait FakesMailListSettings
{
    private function fakeMailListSettings(): void
    {
        SiteSettings::fake([
            'mail_lists' => collect(MemberType::cases())->mapWithKeys(fn (MemberType $type) => [$type->value => [$this->mailListOf($type)]])->all(),
        ]);
    }

    private function mailListOf(MemberType $type): string
    {
        return "$type->value.esac.nl";
    }

    public static function memberTypeChanges(): array
    {
        return collect(MemberType::cases())
            ->crossJoin(MemberType::cases())
            ->reject(fn (array $change) => $change[0] === $change[1])
            ->mapWithKeys(fn (array $change) => ["{$change[0]->value} to {$change[1]->value}" => $change])
            ->all();
    }
}
