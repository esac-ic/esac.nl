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
        Schema::dropIfExists('bibliotheek_old');
        Schema::dropIfExists('telescope_entries_tags');
        Schema::dropIfExists('telescope_entries');
        Schema::dropIfExists('telescope_monitoring');
        Schema::dropIfExists('texts');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('photo_albums');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
