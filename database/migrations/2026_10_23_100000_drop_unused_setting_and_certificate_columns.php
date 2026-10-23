<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Drops the intro package setting, the settings' unused type and the certificates' unused duration.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->where('name', 'SETTING_SHOW_INTRO_OPTION')->delete();

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->integer('duration')->nullable()->after('id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('type')->default('TYPE_STRING')->after('name');
        });
        DB::table('settings')->where('name', 'SETTING_KILLSWITCH')->update(['type' => 'TYPE_BOOLEAN']);
        DB::table('settings')->insert([
            'name' => 'SETTING_SHOW_INTRO_OPTION', 'type' => 'TYPE_BOOLEAN', 'value' => '0', 'created_at' => now(), 'updated_at' => now(),
        ]);

        Schema::table('settings', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }
};
