<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentToMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items',function(Blueprint $table){
            $table->unsignedInteger('content_id')->default(1);

            $table->foreign('content_id')
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
        Schema::table('menu_items',function(Blueprint $table){
            $table->dropForeign(['content_id']);
        });
    }
}
