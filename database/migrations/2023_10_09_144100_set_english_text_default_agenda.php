<?php

use App\AgendaItem;
use App\Text;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetEnglishTextDefaultAgenda extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify columns to be able to store string/text directly.
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->string('title_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('text_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->string('shortDescription_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });

        // Migrate data from texts table to agenda_items.
        $agendaItems = AgendaItem::all();
        foreach ($agendaItems as $item) {
            $title = Text::find($item->title);
            $text = Text::find($item->text);
            $shortDescription = Text::find($item->shortDescription);

            $item->title_string = $title ? $title->EN_text : 'ERROR: TEXT NOT FOUND';
            $item->text_string = $text ? $text->EN_text : 'ERROR: TEXT NOT FOUND';
            $item->shortDescription_string = $shortDescription ? $shortDescription->EN_text : 'ERROR: TEXT NOT FOUND';

            $item->save();
        }

        // Now we'll drop the old columns and foreign keys.
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign(['title']);
            $table->dropForeign(['text']);
            $table->dropForeign(['shortDescription']);

            $table->dropColumn('title');
            $table->dropColumn('text');
            $table->dropColumn('shortDescription');

        });

        DB::statement("ALTER TABLE agenda_items CHANGE title_string title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        DB::statement("ALTER TABLE agenda_items CHANGE text_string text LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        DB::statement("ALTER TABLE agenda_items CHANGE shortDescription_string shortDescription VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('text');
            $table->dropColumn('shortDescription');
        });

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->integer('title')->unsigned()->nullable();
            $table->integer('text')->unsigned()->nullable();
            $table->integer('shortDescription')->unsigned()->nullable();

            $table->foreign('title')->references('id')->on('texts')->onDelete('set null');
            $table->foreign('text')->references('id')->on('texts')->onDelete('set null');
            $table->foreign('shortDescription')->references('id')->on('texts')->onDelete('set null');
        });
    }
}
