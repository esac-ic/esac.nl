<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Gives the pages of the events, the members list and the sign-up the slugs of their URLs.
 */
return new class extends Migration
{
    /**
     * The page slugs, before and after.
     */
    private const array SLUGS = [
        'agenda' => 'events',
        'memberlist' => 'members',
        'signup' => 'sign-up',
    ];

    public function up(): void
    {
        foreach (self::SLUGS as $old => $new) {
            DB::table('menu_items')->where('slug', $old)->update(['slug' => $new]);
        }
    }

    public function down(): void
    {
        foreach (self::SLUGS as $old => $new) {
            DB::table('menu_items')->where('slug', $new)->update(['slug' => $old]);
        }
    }
};
