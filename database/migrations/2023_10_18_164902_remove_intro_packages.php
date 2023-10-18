<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('intro_packages');
        Schema::dropIfExists('user_registration_info');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
