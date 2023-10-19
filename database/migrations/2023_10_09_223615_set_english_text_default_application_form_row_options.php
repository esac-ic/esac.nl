<?php

use App\Models\ApplicationForm\ApplicationFormRowOption;
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
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->string('name_string');
        });

        // Migrate data from texts table to agenda_items categories.
        $items = ApplicationFormRowOption::all();
        foreach ($items as $item) {
            $name = Text::find($item->name_id);
            $name = $name ? $name->EN_text : 'ERROR: TEXT NOT FOUND';
            if (strlen($name) > 255) {
                echo "Text is longer than 255 characters: " . $item->id . "\n";
                #truncate text to 255 characters
                $name = substr($name, 0, 255);
            }
            $item->name_string = $name;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->dropForeign(['name_id']);
            $table->dropColumn('name_id');
        });

        DB::statement("ALTER TABLE application_form_row_options CHANGE name_string name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->integer('name_id')->unsigned()->nullable();
            $table->foreign('name_id')->references('id')->on('texts')->onDelete('set null');
        });
    }
};
