<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Deletes the rejected sign-ups that were only marked as deleted, with their personal details, and the column that marked them.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->whereNotNull('deleted_at')->delete();

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
