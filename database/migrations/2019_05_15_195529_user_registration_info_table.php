<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserRegistrationInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_registration_info', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->boolean('intro_package')->default(false);
            $table->boolean('toprope_course')->default(false);
            $table->string('shirt_size')->nullable();
            $table->date('intro_weekend_date')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->timestamps();
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
        Schema::dropIfExists('user_registration_info');
    }
}
