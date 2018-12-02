<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link')->nullable();
            $table->string('thumbnail')->nullable();
            $table->Integer("width")->default(100);
            $table->Integer("height")->default(100);
            $table->Integer('photo_album_id')->unsigned();
            $table->integer('createdBy')->unsigned();
            $table->timestamps();

            $table->foreign('photo_album_id')->references('id')->on('photo_albums');
            $table->foreign('createdBy')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
