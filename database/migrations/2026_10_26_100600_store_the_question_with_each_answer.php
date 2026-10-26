<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Stores each answer with its question as it was asked, so later edits to a form don't change past registrations.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_answers', function (Blueprint $table) {
            $table->string('question')->default('')->after('registration_form_field_id');
        });

        DB::statement("UPDATE registration_answers a JOIN registration_form_fields f ON f.id = a.registration_form_field_id
            SET a.question = f.name, a.value = CASE f.type WHEN 'checkbox' THEN IF(a.value = 'on', 'Yes', 'No') WHEN 'checkboxes' THEN REPLACE(a.value, ',', ', ') ELSE a.value END");
    }

    public function down(): void
    {
        DB::statement("UPDATE registration_answers a JOIN registration_form_fields f ON f.id = a.registration_form_field_id
            SET a.value = CASE f.type WHEN 'checkbox' THEN IF(a.value = 'Yes', 'on', '0') WHEN 'checkboxes' THEN REPLACE(a.value, ', ', ',') ELSE a.value END");

        Schema::table('registration_answers', function (Blueprint $table) {
            $table->dropColumn('question');
        });
    }
};
