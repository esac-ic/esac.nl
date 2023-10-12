<?php

use App\Models\ApplicationForm\ApplicationFormRowOption;
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
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->string('name_string');
        });

        // Migrate data from texts table to agenda_items categories.
        $items = ApplicationFormRowOption::all();
        foreach ($items as $item) {
            $name = Text::find($item->name_id);
            $item->name_string = $name->EN_text;
            $item->save();
        }

        // Now drop the foreign keys and original columns, then rename the new columns.
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->dropForeign(['name_id']);
            $table->dropColumn('name_id');
            $table->renameColumn('name_string', 'name');
        });
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
