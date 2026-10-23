<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Names the rich text of events and pages body, as it is on news items.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('text', 'body');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('content', 'body');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->renameColumn('body', 'content');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('body', 'text');
        });
    }
};
