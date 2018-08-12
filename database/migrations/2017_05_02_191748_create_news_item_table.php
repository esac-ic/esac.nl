<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('title')->unsigned();
            $table->integer('text')->unsigned();
            $table->string('image_url')->nullable();
            $table->integer('createdBy')->unsigned();
            $table->timestamps();

            $table->foreign('title')
                ->references('id')
                ->on('texts');
            $table->foreign('text')
                ->references('id')
                ->on('texts');
            $table->foreign('createdBy')
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
        Schema::dropIfExists('news_items');
    }
}
