<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponseRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_response_rows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_response_id')->unsigned();
            $table->integer('application_form_row_id')->unsigned();
            $table->string('value');

            $table->timestamps();

            $table->foreign('application_response_id')
                ->references('id')
                ->on('application_responses')
                ->onDelete('cascade');

            $table->foreign('application_form_row_id')
                ->references('id')
                ->on('application_form_rows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_response_rows');
    }
}
