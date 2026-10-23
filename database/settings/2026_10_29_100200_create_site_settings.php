<?php

use App\Enums\MemberType;
use Illuminate\Database\Schema\Blueprint;
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

    public function down(): void
    {
        $settings = DB::table('settings')->where('group', 'site')->pluck('payload', 'name')->map(fn (string $payload) => json_decode($payload, true));

        Schema::create('legacy_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('value');
            $table->timestamps();
        });

        DB::table('legacy_settings')->insert(collect([
            'killswitch' => $settings['killswitch'] ? '1' : '0',
            'footer_phone_number' => (string) $settings['footer_phone_number'],
            'blocked_email_domains' => implode(';', $settings['blocked_email_domains']),
        ])
            ->merge(collect($settings['mail_lists'])->mapWithKeys(fn (array $lists, string $type) => ["{$type}_mail_lists" => implode(';', $lists)]))
            ->map(fn (string $value, string $name) => ['name' => $name, 'value' => $value, 'created_at' => now(), 'updated_at' => now()])
            ->values()
            ->all());

        $settings->keys()->each(fn (string $name) => $this->migrator->delete("site.{$name}"));
    }
};
