<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_items', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('title')->unsigned();
        $table->integer('text')->unsigned();
        $table->integer('shortDescription')->unsigned();
        $table->integer('createdBy')->unsigned();
        $table->integer('category')->unsigned();
        $table->dateTime('startDate');
        $table->dateTime('endDate');
        $table->dateTime('subscription_endDate')->nullable();
        $table->string("image_url");
        $table->integer('application_form_id')->unsigned()->nullable();
        $table->timestamps();

        $table->foreign('title')
            ->references('id')
            ->on('texts');
        $table->foreign('text')
            ->references('id')
            ->on('texts');
        $table->foreign('shortDescription')
            ->references('id')
            ->on('texts');
        $table->foreign('createdBy')
            ->references('id')
            ->on('users');
        $table->foreign('category')
            ->references('id')
            ->on('agenda_item_categories');
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
        Schema::dropIfExists('agenda_items');
    }
}
