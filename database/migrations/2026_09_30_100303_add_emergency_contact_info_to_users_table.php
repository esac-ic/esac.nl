<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('emergencyfirstname')->default("empty")->nullable();
            $table->string('emergencylastname')->default("empty")->nullable();
            $table->string('emergencyrelation')->default("empty")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('emergencyfirstname');
            $table->dropColumn('emergencylastname');
            $table->dropColumn('emergencyrelation');
        });
    }
};
