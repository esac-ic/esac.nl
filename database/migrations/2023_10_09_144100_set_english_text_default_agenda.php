<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Text;
use App\AgendaItem;

class SetEnglishTextDefaultAgenda extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify columns to be able to store string/text directly.
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->string('title_string')->nullable();
            $table->text('text_string')->nullable();
            $table->string('shortDescription_string')->nullable();
        });
    
        // Migrate data from texts table to agenda_items.
        $agendaItems = AgendaItem::all();
        foreach ($agendaItems as $item) {
            $title = Text::find($item->title);
            $text = Text::find($item->text);
            $shortDescription = Text::find($item->shortDescription);
    
            $item->title_string = $title ? $title->EN_text : null;
            $item->text_string = $text ? $text->EN_text : null;
            $item->shortDescription_string = $shortDescription ? $shortDescription->EN_text : null;
    
            $item->save();
        }
    
        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign(['title']);
            $table->dropForeign(['text']);
            $table->dropForeign(['shortDescription']);
    
            $table->dropColumn('title');
            $table->dropColumn('text');
            $table->dropColumn('shortDescription');
    
            $table->renameColumn('title_string', 'title');
            $table->renameColumn('text_string', 'text');
            $table->renameColumn('shortDescription_string', 'shortDescription');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}
