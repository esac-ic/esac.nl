<?php

namespace Tests\Concerns;

use App\Enums\MemberType;
use App\Models\Setting;

/**
 * Gives every member type its own mail list in the settings.
 */
trait FakesMailListSettings
{
    private function fakeMailListSettings(): void
    {
        Setting::query()->delete();

        foreach (MemberType::cases() as $type) {
            Setting::create([
                'name' => $type->mailListSetting()->value,
                'value' => $this->mailListOf($type),
            ]);
        }
    }

    private function mailListOf(MemberType $type): string
    {
        return "$type->value.esac.nl";
    }

    public static function memberTypes(): array
    {
        return collect(MemberType::cases())->mapWithKeys(fn (MemberType $type) => [$type->value => [$type]])->all();
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
