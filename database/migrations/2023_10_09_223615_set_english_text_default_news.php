<?php

use App\NewsItem;
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
        Schema::table('news_items', function (Blueprint $table) {
            $table->string('title_string')->nullable();
            $table->text('text_string')->nullable();
        });

        // Migrate data from texts table to agenda_items categories.
        $news_items = NewsItem::all();
        foreach ($news_items as $item) {
            $title = Text::find($item->title);
            $text = Text::find($item->text);
            $item->title_string = $title ? $title->EN_text : null;
            $item->text_string = $text ? $text->EN_text : null;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('news_items', function (Blueprint $table) {
            $table->dropForeign(['title']);
            $table->dropForeign(['text']);
            $table->dropColumn('title');
            $table->dropColumn('text');
            $table->renameColumn('title_string', 'title');
            $table->renameColumn('text_string', 'text');
        });
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
