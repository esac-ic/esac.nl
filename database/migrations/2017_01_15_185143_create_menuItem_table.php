<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('name')->unsigned();
            //can only be set in the database and is ment for a few pages like the home page and contact page
            $table->boolean('deletable');
            $table->boolean('editable');// if it is set to false only the name and order can be changed
            $table->integer('after')->unsigned()->nullable();
            $table->boolean('login');
            $table->boolean('menuItem');
            $table->string('urlName');
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
        Schema::dropIfExists('menu_items');
    }
}
