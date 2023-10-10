<?php

use App\MenuItem;
use App\Text;
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
        // Modify columns to be able to store string/text directly.
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('name_string')->nullable();
            $table->text('content_string')->nullable();
        });

        // Migrate data from texts table to agenda_items categories.
        $menu_items = MenuItem::all();
        foreach ($menu_items as $item) {
            $name = Text::find($item->name);
            $content = Text::find($item->content_id);
            $item->name_string = $name ? $name->EN_text : null;
            $item->content_string = $content ? $content->EN_text : null;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['name']);
            $table->dropForeign(['content_id']);
            $table->dropColumn('name');
            $table->dropColumn('content_id');
            $table->renameColumn('name_string', 'name');
            $table->renameColumn('content_string', 'content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
