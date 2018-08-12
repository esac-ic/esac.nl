<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendaItemCategorieTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda_item_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->unsigned();

            $table->timestamps();

            $table->foreign('name')
                ->references('id')
                ->on('texts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda_item_categories');
    }
}
