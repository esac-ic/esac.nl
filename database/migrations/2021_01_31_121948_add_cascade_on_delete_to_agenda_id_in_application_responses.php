<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeOnDeleteToAgendaIdInApplicationResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_responses', function (Blueprint $table) {
            $table->dropForeign(['agenda_id']);
            $table->foreign('agenda_id')
                ->references('id')
                ->on('agenda_items')
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
        Schema::table('application_responses', function (Blueprint $table) {
            $table->dropForeign(['agenda_id']);
            $table->foreign('agenda_id')
                ->references('id')
                ->on('agenda_items');
        });
    }
}
