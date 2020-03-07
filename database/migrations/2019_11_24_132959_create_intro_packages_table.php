<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntroPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intro_packages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('name')->unsigned();
            $table->date('deadline');
            $table->integer('application_form_id')->unsigned();

            $table->timestamps();

            $table->foreign('name')->references('id')->on('texts');
            $table->foreign('application_form_id')->references('id')->on('application_forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intro_packages');
    }
}
