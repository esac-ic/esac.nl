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
        // remove:oldApplications anonymises old registrations by unlinking the member
        Schema::table('application_responses', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_responses', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable(false)->change();
        });
    }
};
