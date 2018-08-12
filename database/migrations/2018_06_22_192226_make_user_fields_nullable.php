<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeUserFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',function(Blueprint $table){
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('houseNumber')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('zipcode')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->string('phonenumber')->nullable()->change();
            $table->string('emergencyNumber')->nullable()->change();
            $table->string('emergencyHouseNumber')->nullable()->change();
            $table->string('emergencystreet')->nullable()->change();
            $table->string('emergencycity')->nullable()->change();
            $table->string('emergencyzipcode')->nullable()->change();
            $table->string('emergencycountry')->nullable()->change();
            $table->string('birthDay')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('kind_of_member')->nullable()->change();
            $table->string('IBAN')->nullable()->change();
            $table->string('BIC')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
