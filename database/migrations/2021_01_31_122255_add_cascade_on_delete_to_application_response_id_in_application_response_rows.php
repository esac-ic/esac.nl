<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeOnDeleteToApplicationResponseIdInApplicationResponseRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_response_rows', function (Blueprint $table) {
            $table->dropForeign(['application_response_id']);
            $table->foreign('application_response_id')
                ->references('id')
                ->on('application_responses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_response_rows', function (Blueprint $table) {
            $table->dropForeign(['application_response_id']);
            $table->foreign('application_response_id')
                ->references('id')
                ->on('application_responses');
        });
    }
}
