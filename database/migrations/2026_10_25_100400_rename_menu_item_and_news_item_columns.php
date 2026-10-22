<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Names the menu item flags after what they mean, url_name the slug it is, and the news item columns after what they hold.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->renameColumn('menu_item', 'show_in_menu');
            $table->renameColumn('login', 'requires_login');
            $table->renameColumn('url_name', 'slug');
        });

        Schema::table('news_items', function (Blueprint $table) {
            $table->renameColumn('text', 'body');
            $table->renameColumn('image_url', 'image_path');
            $table->renameColumn('thumbnail_url', 'thumbnail_path');
        });
    }

    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->renameColumn('body', 'text');
            $table->renameColumn('image_path', 'image_url');
            $table->renameColumn('thumbnail_path', 'thumbnail_url');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->renameColumn('show_in_menu', 'menu_item');
            $table->renameColumn('requires_login', 'login');
            $table->renameColumn('slug', 'url_name');
        });
    }
};
