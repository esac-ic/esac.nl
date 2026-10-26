<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Deletes the cancelled registrations that were only marked as deleted, with their answers, and the columns that marked them.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('registrations')->whereNotNull('deleted_at')->delete();
        DB::table('registration_answers')->whereNotNull('deleted_at')->delete();

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('registration_answers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('registration_answers', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};
