<?php

use App\Models\ApplicationForm\ApplicationFormRow;
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
        Schema::table('application_form_rows', function (Blueprint $table) {
            $table->string('name_string')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
        });

        // Migrate data from texts table to agenda_items categories.
        $items = ApplicationFormRow::all();
        foreach ($items as $item) {
            $name = Text::find($item->name);

            if (!$name) {
                echo "No text found for row id: " . $item->id . " and name " . $item->name . "\n";
                # Set name to error text
                $name = new Text();
                $name->EN_text = "ERROR: Text not found";
            }

            $item->name_string = $name->EN_text;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('application_form_rows', function (Blueprint $table) {
            $table->dropForeign(['name']);
            $table->dropColumn('name');
        });

        DB::statement("ALTER TABLE application_form_rows CHANGE name_string name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form_rows', function (Blueprint $table) {

            $table->dropColumn('name');
        });

        Schema::table('application_form_rows', function (Blueprint $table) {
            $table->integer('name')->unsigned()->nullable();
            $table->foreign('name')->references('id')->on('texts')->onDelete('set null');
        });
    }
};
