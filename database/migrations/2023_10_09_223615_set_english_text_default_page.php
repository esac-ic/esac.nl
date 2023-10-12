<?php

use App\MenuItem;
use App\Text;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify columns to be able to store string/text directly.
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('name_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('content_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });

        // Migrate data from texts table to agenda_items categories.
        $menu_items = MenuItem::all();
        foreach ($menu_items as $item) {
            $name = Text::find($item->name);
            $content = Text::find($item->content_id);
            $item->name_string = $name ? $name->EN_text : 'ERROR: TEXT NOT FOUND';
            $item->content_string = $content ? $content->EN_text : 'ERROR: TEXT NOT FOUND';
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['name']);
            $table->dropForeign(['content_id']);
            $table->dropColumn('name');
            $table->dropColumn('content_id');
        });

        DB::statement("ALTER TABLE menu_items CHANGE name_string name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        DB::statement("ALTER TABLE menu_items CHANGE content_string content LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('content');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->integer('name')->unsigned()->nullable();
            $table->integer('content_id')->unsigned()->nullable();
            $table->foreign('name')->references('id')->on('texts')->onDelete('set null');
            $table->foreign('content_id')->references('id')->on('texts')->onDelete('set null');
        });
    }
};
