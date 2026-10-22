<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The columns to rename, per table, from the old name to the snake_case name.
     */
    private const array COLUMNS = [
        'agenda_items' => [
            'createdBy' => 'created_by',
            'startDate' => 'start_date',
            'endDate' => 'end_date',
            'subscription_endDate' => 'subscription_end_date',
            'shortDescription' => 'short_description',
        ],
        'menu_items' => [
            'menuItem' => 'menu_item',
            'urlName' => 'url_name',
        ],
        'users' => [
            'houseNumber' => 'house_number',
            'phonenumber' => 'phone_number',
            'phonenumber_alt' => 'phone_number_alt',
            'emergencyNumber' => 'emergency_number',
            'emergencyHouseNumber' => 'emergency_house_number',
            'emergencystreet' => 'emergency_street',
            'emergencycity' => 'emergency_city',
            'emergencyzipcode' => 'emergency_zipcode',
            'emergencycountry' => 'emergency_country',
            'birthDay' => 'birthday',
            'IBAN' => 'iban',
            'BIC' => 'bic',
        ],
        'zekerings' => [
            'createdBy' => 'created_by',
        ],
    ];

    /**
     * The tables whose createdBy column has a foreign key to users, so its name has to change with the column.
     */
    private const array CREATED_BY_FOREIGN_KEYS = ['agenda_items', 'zekerings'];

    public function up(): void
    {
        foreach (self::CREATED_BY_FOREIGN_KEYS as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign($table->getTable().'_createdby_foreign');
                $table->dropIndex($table->getTable().'_createdby_foreign');
            });
        }

        foreach (self::COLUMNS as $table => $columns) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                foreach ($columns as $from => $to) {
                    $table->renameColumn($from, $to);
                }
            });
        }

        foreach (self::CREATED_BY_FOREIGN_KEYS as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreign('created_by')->references('id')->on('users');
            });
        }
    }

    public function down(): void
    {
        foreach (self::CREATED_BY_FOREIGN_KEYS as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign($table->getTable().'_created_by_foreign');
                $table->dropIndex($table->getTable().'_created_by_foreign');
            });
        }

        foreach (self::COLUMNS as $table => $columns) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                foreach ($columns as $from => $to) {
                    $table->renameColumn($to, $from);
                }
            });
        }

        foreach (self::CREATED_BY_FOREIGN_KEYS as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreign('createdBy', $table->getTable().'_createdby_foreign')->references('id')->on('users');
            });
        }
    }
};
