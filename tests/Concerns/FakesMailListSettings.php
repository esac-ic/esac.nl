<?php

namespace Tests\Concerns;

use App\Enums\KindOfMember;
use App\Models\Setting;

/**
 * Gives every kind of member its own mail list in the settings.
 */
trait FakesMailListSettings
{
    private function fakeMailListSettings(): void
    {
        Setting::truncate();

        foreach (KindOfMember::cases() as $kind) {
            Setting::create([
                'name' => $kind->mailListSetting()->value,
                'value' => $this->mailListOf($kind),
            ]);
        }
    }

    private function mailListOf(KindOfMember $kind): string
    {
        return "$kind->value.esac.nl";
    }

    public static function kindsOfMember(): array
    {
        return collect(KindOfMember::cases())->mapWithKeys(fn (KindOfMember $kind) => [$kind->value => [$kind]])->all();
    }

    public static function kindChanges(): array
    {
        return collect(KindOfMember::cases())
            ->crossJoin(KindOfMember::cases())
            ->reject(fn (array $change) => $change[0] === $change[1])
            ->mapWithKeys(fn (array $change) => ["{$change[0]->value} to {$change[1]->value}" => $change])
            ->all();
    }
}
