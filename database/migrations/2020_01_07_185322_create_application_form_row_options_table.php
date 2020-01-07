<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationFormRowOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_form_row_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->nullable();
            $table->unsignedInteger('name_id');
            $table->unsignedInteger('application_form_row_id');

            $table->foreign('name_id')->references('id')->on('texts');
            $table->foreign('application_form_row_id')->references('id')->on('application_form_rows');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_form_row_options');
    }
}
