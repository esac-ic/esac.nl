<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            //user information
            $table->string('firstname');
            $table->string('preposition')->nullable();
            $table->string('lastname');
            $table->string('initials')->nullable();
            $table->string('street');
            $table->string('houseNumber');
            $table->string('city');
            $table->string('zipcode');
            $table->string('country');
            $table->string('phonenumber');
            $table->string('phonenumber_alt')->nullable(); //alternative phonenumber
            $table->string('emergencyNumber');
            $table->string('emergencyHouseNumber');
            $table->string('emergencystreet');
            $table->string('emergencycity');
            $table->string('emergencyzipcode');
            $table->string('emergencycountry');
            $table->date('birthDay');
            $table->string('gender');
            $table->string('kind_of_member'); //todo make it so the kind of members are dynamic
            $table->integer('nkbv_nr');
            $table->boolean('climbingCard');
            $table->boolean('travelInsurance');
            $table->string('studendNumber')->nullable();
            $table->string('study');
            $table->string('IBAN');
            $table->string('BIC')->nullable();
            $table->boolean('incasso');
            $table->longText('remark')->nullable();
            $table->date('lid_af')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
