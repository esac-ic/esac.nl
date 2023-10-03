<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSelfReferencingZekeringRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make parent_id nullable and set default as NULL
        Schema::table('zekerings', function (Blueprint $table) {
            $table->integer('parent_id')->nullable()->default(null)->change();
        });

        // Update records where id is the same as parent_id and set parent_id to null
        \DB::table('zekerings')
            ->whereColumn('id', 'parent_id')
            ->update(['parent_id' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reset parent_id to non-nullable and set default value as 0
        Schema::table('zekerings', function (Blueprint $table) {
            $table->integer('parent_id')->default(0)->change();
        });
    }
}
