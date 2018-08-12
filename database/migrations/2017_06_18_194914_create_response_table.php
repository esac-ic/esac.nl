<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agenda_id')->unsigned();
            $table->integer('inschrijf_form_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->timestamps();

            $table->foreign('inschrijf_form_id')
                ->references('id')
                ->on('application_forms');

            $table->foreign('agenda_id')
                ->references('id')
                ->on('agenda_items');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_responses');
    }
}
