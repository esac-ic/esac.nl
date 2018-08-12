<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZekeringenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zekerings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->integer('createdBy')->unsigned();
            $table->integer('score');
            $table->timestamps();

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
        Schema::dropIfExists('zekerings');
    }
}
