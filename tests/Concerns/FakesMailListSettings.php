<?php

namespace Tests\Concerns;

use App\Enums\SettingKey;
use App\Models\Setting;
use App\Models\User;

/**
 * Gives every kind of member its own mail list in the settings.
 */
trait FakesMailListSettings
{
    private function fakeMailListSettings(): void
    {
        Setting::truncate();

        foreach (User::KINDS_OF_MEMBER as $kind) {
            Setting::create([
                'name' => SettingKey::mailListsFor($kind)->value,
                'type' => Setting::TYPE_STRING,
                'value' => $this->mailListOf($kind),
            ]);
        }
    }

    private function mailListOf(string $kind): string
    {
        return "$kind.esac.nl";
    }

    public static function kindsOfMember(): array
    {
        return collect(User::KINDS_OF_MEMBER)->mapWithKeys(fn ($kind) => [$kind => [$kind]])->all();
    }

    public static function kindChanges(): array
    {
        return collect(User::KINDS_OF_MEMBER)
            ->crossJoin(User::KINDS_OF_MEMBER)
            ->reject(fn ($change) => $change[0] === $change[1])
            ->mapWithKeys(fn ($change) => ["$change[0] to $change[1]" => $change])
            ->all();
    }
}
