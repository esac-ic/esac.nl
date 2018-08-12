<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnnecessaryUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'initials',
                'nkbv_nr',
                'climbingCard',
                'travelInsurance',
                'studendNumber',
                'study',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('initials')->nullable();
            $table->integer('nkbv_nr')->nullable();
            $table->boolean('climbingCard')->default(0);
            $table->boolean('travelInsurance')->default(0);
            $table->string('studendNumber')->nullable();
            $table->string('study')->nullable();
        });
    }
}
