<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign('agenda_items_createdby_foreign');
            $table->dropIndex('agenda_items_createdby_foreign');
            $table->renameColumn('createdBy', 'created_by');
            $table->renameColumn('startDate', 'start_date');
            $table->renameColumn('endDate', 'end_date');
            $table->renameColumn('subscription_endDate', 'subscription_end_date');
            $table->renameColumn('shortDescription', 'short_description');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->renameColumn('menuItem', 'menu_item');
            $table->renameColumn('urlName', 'url_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('houseNumber', 'house_number');
            $table->renameColumn('phonenumber', 'phone_number');
            $table->renameColumn('phonenumber_alt', 'phone_number_alt');
            $table->renameColumn('emergencyNumber', 'emergency_number');
            $table->renameColumn('emergencyHouseNumber', 'emergency_house_number');
            $table->renameColumn('emergencystreet', 'emergency_street');
            $table->renameColumn('emergencycity', 'emergency_city');
            $table->renameColumn('emergencyzipcode', 'emergency_zipcode');
            $table->renameColumn('emergencycountry', 'emergency_country');
            $table->renameColumn('birthDay', 'birthday');
            $table->renameColumn('IBAN', 'iban');
            $table->renameColumn('BIC', 'bic');
        });

        Schema::table('zekerings', function (Blueprint $table) {
            $table->dropForeign('zekerings_createdby_foreign');
            $table->dropIndex('zekerings_createdby_foreign');
            $table->renameColumn('createdBy', 'created_by');
        });

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::table('zekerings', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign('agenda_items_created_by_foreign');
            $table->dropIndex('agenda_items_created_by_foreign');
            $table->renameColumn('created_by', 'createdBy');
            $table->renameColumn('start_date', 'startDate');
            $table->renameColumn('end_date', 'endDate');
            $table->renameColumn('subscription_end_date', 'subscription_endDate');
            $table->renameColumn('short_description', 'shortDescription');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->renameColumn('menu_item', 'menuItem');
            $table->renameColumn('url_name', 'urlName');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('house_number', 'houseNumber');
            $table->renameColumn('phone_number', 'phonenumber');
            $table->renameColumn('phone_number_alt', 'phonenumber_alt');
            $table->renameColumn('emergency_number', 'emergencyNumber');
            $table->renameColumn('emergency_house_number', 'emergencyHouseNumber');
            $table->renameColumn('emergency_street', 'emergencystreet');
            $table->renameColumn('emergency_city', 'emergencycity');
            $table->renameColumn('emergency_zipcode', 'emergencyzipcode');
            $table->renameColumn('emergency_country', 'emergencycountry');
            $table->renameColumn('birthday', 'birthDay');
            $table->renameColumn('iban', 'IBAN');
            $table->renameColumn('bic', 'BIC');
        });

        Schema::table('zekerings', function (Blueprint $table) {
            $table->dropForeign('zekerings_created_by_foreign');
            $table->dropIndex('zekerings_created_by_foreign');
            $table->renameColumn('created_by', 'createdBy');
        });

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->foreign('createdBy', 'agenda_items_createdby_foreign')->references('id')->on('users');
        });

        Schema::table('zekerings', function (Blueprint $table) {
            $table->foreign('createdBy', 'zekerings_createdby_foreign')->references('id')->on('users');
        });
    }
};
