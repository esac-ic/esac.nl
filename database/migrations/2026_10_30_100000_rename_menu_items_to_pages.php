<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('menu_items', 'pages');
    }

    public function down(): void
    {
        Schema::rename('pages', 'menu_items');
    }
};
