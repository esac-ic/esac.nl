<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames the Dutch and legacy tables, columns and values to English Laravel conventions.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('rols', 'roles');
        Schema::rename('rol_user', 'role_user');
        Schema::table('role_user', function (Blueprint $table) {
            $table->renameColumn('rol_id', 'role_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('lid_af', 'membership_ended_on');
            $table->renameColumn('pending_user', 'pending_since');
            $table->renameColumn('incasso', 'direct_debit');
        });

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign('agenda_items_category_foreign');
            $table->dropIndex('agenda_items_category_foreign');
            $table->renameColumn('category', 'agenda_item_category_id');
        });
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->foreign('agenda_item_category_id')->references('id')->on('agenda_item_categories');
        });

        Schema::table('application_responses', function (Blueprint $table) {
            $table->dropForeign('application_responses_agenda_id_foreign');
            $table->dropIndex('application_responses_agenda_id_foreign');
            $table->dropForeign('application_responses_inschrijf_form_id_foreign');
            $table->dropIndex('application_responses_inschrijf_form_id_foreign');
            $table->renameColumn('agenda_id', 'agenda_item_id');
            $table->renameColumn('inschrijf_form_id', 'application_form_id');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->foreign('agenda_item_id')->references('id')->on('agenda_items')->cascadeOnDelete();
            $table->foreign('application_form_id')->references('id')->on('application_forms');
        });

        DB::table('application_form_rows')->where('type', 'checkboxen')->update(['type' => 'checkboxes']);
    }

    public function down(): void
    {
        DB::table('application_form_rows')->where('type', 'checkboxes')->update(['type' => 'checkboxen']);

        Schema::table('application_responses', function (Blueprint $table) {
            $table->dropForeign('application_responses_agenda_item_id_foreign');
            $table->dropIndex('application_responses_agenda_item_id_foreign');
            $table->dropForeign('application_responses_application_form_id_foreign');
            $table->dropIndex('application_responses_application_form_id_foreign');
            $table->renameColumn('agenda_item_id', 'agenda_id');
            $table->renameColumn('application_form_id', 'inschrijf_form_id');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->foreign('agenda_id', 'application_responses_agenda_id_foreign')->references('id')->on('agenda_items')->cascadeOnDelete();
            $table->foreign('inschrijf_form_id', 'application_responses_inschrijf_form_id_foreign')->references('id')->on('application_forms');
        });

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->dropForeign('agenda_items_agenda_item_category_id_foreign');
            $table->dropIndex('agenda_items_agenda_item_category_id_foreign');
            $table->renameColumn('agenda_item_category_id', 'category');
        });
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->foreign('category', 'agenda_items_category_foreign')->references('id')->on('agenda_item_categories');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('membership_ended_on', 'lid_af');
            $table->renameColumn('pending_since', 'pending_user');
            $table->renameColumn('direct_debit', 'incasso');
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->renameColumn('role_id', 'rol_id');
        });
        Schema::rename('role_user', 'rol_user');
        Schema::rename('roles', 'rols');
    }
};
