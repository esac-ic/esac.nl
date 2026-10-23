<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Makes room for the settings table of spatie/laravel-settings; the site settings migration moves the values over.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('settings', 'legacy_settings');
    }

    public function down(): void
    {
        Schema::rename('legacy_settings', 'settings');
    }
};
