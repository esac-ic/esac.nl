<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserEventTypes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_event_log_entries', function (Blueprint $table) {
            $table->id();
            
            //custom fields corresponding to the UserEventLogEntry model
            $table->string('eventType')->default(UserEventTypes::MemberTypeChanged);
            $table->string('eventDetails');
            $table->unsignedInteger('user_id')->nullable(); //nullable because users can be deleted and we don't want to also delete the log entry
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_event_log_entries', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('user_event_log_entries');
    }
};
