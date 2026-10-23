<?php

use App\Enums\MemberType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Moves the values of the legacy settings table, where lists were separated by ;, to the site settings.
 */
return new class extends SettingsMigration
{
    public function up(): void
    {
        $values = DB::table('legacy_settings')->pluck('value', 'name');
        $list = fn (string $name) => str($values[$name])->explode(';')->map(fn (string $item) => trim($item))->filter()->values()->all();

        $this->migrator->add('site.killswitch', (bool) $values['killswitch']);
        $this->migrator->add('site.footer_phone_number', $values['footer_phone_number'] ?: null);
        $this->migrator->add('site.blocked_email_domains', $list('blocked_email_domains'));
        $this->migrator->add('site.mail_lists', collect(MemberType::cases())
            ->mapWithKeys(fn (MemberType $type) => [$type->value => $list("{$type->value}_mail_lists")])
            ->all());

        Schema::drop('legacy_settings');
    }
};
