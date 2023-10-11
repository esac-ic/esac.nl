<?php

use App\AgendaItemCategory;
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
        Schema::table('agenda_item_categories', function (Blueprint $table) {
            $table->string('name_string')->nullable();
        });

        // Migrate data from texts table to agenda_items categories.
        $agendaItemCategories = AgendaItemCategory::all();
        foreach ($agendaItemCategories as $item) {
            $name = Text::find($item->name);
            $item->name_string = $name ? $name->EN_text : null;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('agenda_item_categories', function (Blueprint $table) {
            $table->dropForeign(['name']);
            $table->dropColumn('name');
            $table->renameColumn('name_string', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_item_categories', function (Blueprint $table) {

            $table->dropColumn('name');
        });

        Schema::table('agenda_item_categories', function (Blueprint $table) {
            $table->integer('name')->unsigned()->nullable();
            $table->foreign('name')->references('id')->on('texts')->onDelete('set null');
        });
    }
};
