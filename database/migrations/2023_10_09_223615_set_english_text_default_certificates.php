<?php

use App\Certificate;
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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('name_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });

        // Migrate data from texts table to agenda_items categories.
        $certificates = Certificate::all();
        foreach ($certificates as $item) {
            $name = Text::find($item->name);
            $item->name_string = $name->EN_text;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('certificates', function (Blueprint $table) {
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
        Schema::table('certificates', function (Blueprint $table) {

            $table->dropColumn('name');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->integer('name')->unsigned()->nullable();
            $table->foreign('name')->references('id')->on('texts')->onDelete('set null');
        });
    }
};
