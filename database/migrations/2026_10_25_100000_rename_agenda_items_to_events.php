<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames agenda items to events, their columns to what they hold and the activity administrator role to event administrator.
 * The image files keep their agendaItem/ paths.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_responses', function (Blueprint $table) {
            $table->dropForeign('application_responses_agenda_item_id_foreign');
            $table->dropIndex('application_responses_agenda_item_id_foreign');
            $table->renameColumn('agenda_item_id', 'event_id');
        });

        Schema::table('agenda_items', function (Blueprint $table) {
            foreach (['created_by', 'agenda_item_category_id', 'application_form_id'] as $column) {
                $table->dropForeign("agenda_items_{$column}_foreign");
                $table->dropIndex("agenda_items_{$column}_foreign");
            }
            $table->renameColumn('agenda_item_category_id', 'event_category_id');
            $table->renameColumn('subscription_end_date', 'registration_deadline');
            $table->renameColumn('image_url', 'image_path');
            $table->renameColumn('climbing_activity', 'shows_certificates');
        });

        Schema::rename('agenda_item_categories', 'event_categories');
        Schema::rename('agenda_items', 'events');

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('event_category_id')->references('id')->on('event_categories');
            $table->foreign('application_form_id')->references('id')->on('application_forms');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
        });

        DB::table('roles')->where('id', 3)->where('name', 'Activity administrator')->update(['name' => 'Event administrator']);
    }

    public function down(): void
    {
        DB::table('roles')->where('id', 3)->where('name', 'Event administrator')->update(['name' => 'Activity administrator']);

        Schema::table('application_responses', function (Blueprint $table) {
            $table->dropForeign('application_responses_event_id_foreign');
            $table->dropIndex('application_responses_event_id_foreign');
        });
        Schema::table('events', function (Blueprint $table) {
            foreach (['created_by', 'event_category_id', 'application_form_id'] as $column) {
                $table->dropForeign("events_{$column}_foreign");
                $table->dropIndex("events_{$column}_foreign");
            }
        });

        Schema::rename('events', 'agenda_items');
        Schema::rename('event_categories', 'agenda_item_categories');

        Schema::table('agenda_items', function (Blueprint $table) {
            $table->renameColumn('event_category_id', 'agenda_item_category_id');
            $table->renameColumn('registration_deadline', 'subscription_end_date');
            $table->renameColumn('image_path', 'image_url');
            $table->renameColumn('shows_certificates', 'climbing_activity');
        });
        Schema::table('agenda_items', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('agenda_item_category_id')->references('id')->on('agenda_item_categories');
            $table->foreign('application_form_id')->references('id')->on('application_forms');
        });

        Schema::table('application_responses', function (Blueprint $table) {
            $table->renameColumn('event_id', 'agenda_item_id');
        });
        Schema::table('application_responses', function (Blueprint $table) {
            $table->foreign('agenda_item_id')->references('id')->on('agenda_items')->cascadeOnDelete();
        });
    }
};
