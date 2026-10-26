<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A member registers once per event: keeps their first registration and deletes the later duplicates with their answers.
 */
return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('registrations as later')
            ->join('registrations as first', fn ($join) => $join->on('first.event_id', 'later.event_id')->on('first.user_id', 'later.user_id')->on('first.id', '<', 'later.id'))
            ->distinct()
            ->pluck('later.id');
        DB::table('registrations')->whereIn('id', $duplicates)->delete();

        Schema::table('registrations', function (Blueprint $table) {
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'user_id']);
        });
    }
};
