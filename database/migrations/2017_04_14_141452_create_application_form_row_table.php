<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationFormRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_form_rows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->unsigned();
            $table->integer('application_form_id')->unsigned();
            $table->string('type');
            $table->boolean('required');
            $table->timestamps();

            $table->foreign('name')
                ->references('id')
                ->on('texts');

            $table->foreign('application_form_id')
                ->references('id')
                ->on('application_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_form_rows');
    }
}
