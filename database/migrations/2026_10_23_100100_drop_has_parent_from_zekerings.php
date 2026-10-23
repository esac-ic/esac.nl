<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * has_parent always equals parent_id IS NOT NULL, so it's derived from parent_id instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zekerings', function (Blueprint $table) {
            $table->dropColumn('has_parent');
        });
    }

    public function down(): void
    {
        Schema::table('zekerings', function (Blueprint $table) {
            $table->boolean('has_parent')->default(false)->after('parent_id');
        });

        DB::table('zekerings')->whereNotNull('parent_id')->update(['has_parent' => true]);
    }
};
