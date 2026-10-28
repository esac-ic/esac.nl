<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::all()->each(function ($user) {
            // Force the mutator to run to format the IBAN
            $user->IBAN = $user->IBAN;
            $user->save();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('BIC');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('BIC', 255)->nullable();
        });
    }
};
