<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_form_rows', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('application_forms', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('application_response_rows', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('application_responses', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('texts', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('agenda_item_categories', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('certificates', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('rols', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('front_end_replacements', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('menu_items', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('news_items', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('zekerings', function($table)
        {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
