<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCreatedByFieldFromNewsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_items',function(Blueprint $table){
            $table->dropForeign(['createdBy']);
            $table->dropColumn('createdBy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_items',function(Blueprint $table){
            $table->unsignedInteger('createdBy')->nulabble();

            $table->foreign('createdBy')
                ->references('id')
                ->on('users');
        });
    }
}
