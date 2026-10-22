<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames application forms, rows and options to registration forms, fields and options, and application
 * responses and their rows to registrations and answers. The textBox field type becomes textarea.
 */
return new class extends Migration
{
    private const string OPTIONS_FIELD_FOREIGN_KEY = 'registration_form_field_options_field_id_foreign';

    public function up(): void
    {
        $this->dropForeignKeys([
            'events' => ['application_form_id'],
            'application_form_rows' => ['application_form_id'],
            'application_form_row_options' => ['application_form_row_id'],
            'application_responses' => ['event_id', 'application_form_id', 'user_id'],
            'application_response_rows' => ['application_response_id', 'application_form_row_id'],
        ]);

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('application_form_id', 'registration_form_id');
        });
        Schema::table('application_form_rows', function (Blueprint $table) {
            $table->renameColumn('application_form_id', 'registration_form_id');
        });
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->renameColumn('application_form_row_id', 'registration_form_field_id');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->renameColumn('application_form_id', 'registration_form_id');
        });
        Schema::table('application_response_rows', function (Blueprint $table) {
            $table->renameColumn('application_response_id', 'registration_id');
            $table->renameColumn('application_form_row_id', 'registration_form_field_id');
        });

        Schema::rename('application_forms', 'registration_forms');
        Schema::rename('application_form_rows', 'registration_form_fields');
        Schema::rename('application_form_row_options', 'registration_form_field_options');
        Schema::rename('application_responses', 'registrations');
        Schema::rename('application_response_rows', 'registration_answers');

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('registration_form_id')->references('id')->on('registration_forms');
        });
        Schema::table('registration_form_fields', function (Blueprint $table) {
            $table->foreign('registration_form_id')->references('id')->on('registration_forms');
        });
        Schema::table('registration_form_field_options', function (Blueprint $table) {
            // The conventional name is longer than MySQL's 64 characters.
            $table->foreign('registration_form_field_id', self::OPTIONS_FIELD_FOREIGN_KEY)->references('id')->on('registration_form_fields');
        });
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('registration_form_id')->references('id')->on('registration_forms');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('registration_answers', function (Blueprint $table) {
            $table->foreign('registration_id')->references('id')->on('registrations')->cascadeOnDelete();
            $table->foreign('registration_form_field_id')->references('id')->on('registration_form_fields');
        });

        DB::table('registration_form_fields')->where('type', 'textBox')->update(['type' => 'textarea']);
    }

    public function down(): void
    {
        DB::table('registration_form_fields')->where('type', 'textarea')->update(['type' => 'textBox']);

        Schema::table('registration_form_field_options', function (Blueprint $table) {
            $table->dropForeign(self::OPTIONS_FIELD_FOREIGN_KEY);
            $table->dropIndex(self::OPTIONS_FIELD_FOREIGN_KEY);
        });
        $this->dropForeignKeys([
            'events' => ['registration_form_id'],
            'registration_form_fields' => ['registration_form_id'],
            'registrations' => ['event_id', 'registration_form_id', 'user_id'],
            'registration_answers' => ['registration_id', 'registration_form_field_id'],
        ]);

        Schema::rename('registration_answers', 'application_response_rows');
        Schema::rename('registrations', 'application_responses');
        Schema::rename('registration_form_field_options', 'application_form_row_options');
        Schema::rename('registration_form_fields', 'application_form_rows');
        Schema::rename('registration_forms', 'application_forms');

        Schema::table('application_response_rows', function (Blueprint $table) {
            $table->renameColumn('registration_id', 'application_response_id');
            $table->renameColumn('registration_form_field_id', 'application_form_row_id');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->renameColumn('registration_form_id', 'application_form_id');
        });
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->renameColumn('registration_form_field_id', 'application_form_row_id');
        });
        Schema::table('application_form_rows', function (Blueprint $table) {
            $table->renameColumn('registration_form_id', 'application_form_id');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('registration_form_id', 'application_form_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('application_form_id')->references('id')->on('application_forms');
        });
        Schema::table('application_form_rows', function (Blueprint $table) {
            $table->foreign('application_form_id')->references('id')->on('application_forms');
        });
        Schema::table('application_form_row_options', function (Blueprint $table) {
            $table->foreign('application_form_row_id')->references('id')->on('application_form_rows');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('application_form_id')->references('id')->on('application_forms');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('application_response_rows', function (Blueprint $table) {
            $table->foreign('application_response_id')->references('id')->on('application_responses')->cascadeOnDelete();
            $table->foreign('application_form_row_id')->references('id')->on('application_form_rows');
        });
    }

    /**
     * Drops the foreign keys, named by Laravel's convention, and the indexes MySQL made for them.
     *
     * @param  array<string, list<string>>  $columnsByTable
     */
    private function dropForeignKeys(array $columnsByTable): void
    {
        foreach ($columnsByTable as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($table, $columns) {
                foreach ($columns as $column) {
                    $blueprint->dropForeign("{$table}_{$column}_foreign");
                    $blueprint->dropIndex("{$table}_{$column}_foreign");
                }
            });
        }
    }
};
