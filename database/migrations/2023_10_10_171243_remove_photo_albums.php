<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RemovePhotoAlbums extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('photos');
        Schema::dropIfExists('photo_albums');}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
