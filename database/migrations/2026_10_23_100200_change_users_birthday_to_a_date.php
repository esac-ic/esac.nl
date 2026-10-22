<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Birthdays are stored as 'Y-m-d H:i:s' or 'Y-m-d' text, or left empty.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('birthday', '')->update(['birthday' => null]);
        DB::table('users')->whereNotNull('birthday')->update(['birthday' => DB::raw('LEFT(birthday, 10)')]);

        Schema::table('users', function (Blueprint $table) {
            $table->date('birthday')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('birthday')->nullable()->change();
        });
    }
};
