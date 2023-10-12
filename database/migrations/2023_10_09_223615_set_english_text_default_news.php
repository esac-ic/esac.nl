<?php

use App\NewsItem;
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
        Schema::table('news_items', function (Blueprint $table) {
            $table->string('title_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->longText('text_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });

        // Migrate data from texts table to agenda_items categories.
        $news_items = NewsItem::all();
        foreach ($news_items as $item) {
            $title = Text::find($item->title);
            $text = Text::find($item->text);
            $item->title_string = $title->EN_text;
            $item->text_string = $text->EN_text;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropForeign(['title']);
            $table->dropForeign(['text']);
            $table->dropColumn('title');
            $table->dropColumn('text');
        });

        DB::statement("ALTER TABLE news_items CHANGE title_string title VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        DB::statement("ALTER TABLE news_items CHANGE text_string text LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('text');
        });

        Schema::table('news_items', function (Blueprint $table) {
            $table->integer('title')->unsigned()->nullable();
            $table->integer('text')->unsigned()->nullable();
            $table->foreign('title')->references('id')->on('texts')->onDelete('set null');
            $table->foreign('text')->references('id')->on('texts')->onDelete('set null');
        });
    }
};
