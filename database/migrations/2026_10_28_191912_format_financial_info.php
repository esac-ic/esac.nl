<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Aint no undoing this :)
    }
};
