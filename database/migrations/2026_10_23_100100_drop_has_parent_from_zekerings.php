<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * A zekering's parent is derived from parent_id instead. Older top-level zekeringen point parent_id at
 * themselves, so top-level rows get a null parent_id first.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('zekerings')->where('has_parent', false)->update(['parent_id' => null]);

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
