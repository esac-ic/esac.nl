<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames the kind of member to member type (with the participant names of the statutes), the user event log to the member log
 * with the renamed event classes as its types, and drops the SETTING_ prefix from the setting names.
 */
return new class extends Migration
{
    /**
     * The logged event class names, before and after.
     */
    private const array LOG_TYPES = [
        'MemberKindChanged' => 'MemberTypeChanged',
        'MemberBecameOldMember' => 'MembershipEnded',
        'OldMemberBecameMember' => 'MembershipRestored',
        'PendingUserCreated' => 'MemberSignedUp',
        'PendingUserApproved' => 'SignUpApproved',
        'PendingUserRemoved' => 'SignUpRejected',
    ];

    private const array MEMBER_TYPES = [
        'extraordinary_member' => 'extraordinary_participant',
        'member_of_merit' => 'member_of_service',
    ];

    private const array SETTINGS = [
        'SETTING_BLOCKED_EMAIL_DOMAINS' => 'BLOCKED_EMAIL_DOMAINS',
        'SETTING_KILLSWITCH' => 'KILLSWITCH',
        'SETTING_FOOTER_PHONE_NR' => 'FOOTER_PHONE_NUMBER',
        'SETTING_NORMAL_MEMBER_MAIL_LISTS' => 'MEMBER_MAIL_LISTS',
        'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS' => 'EXTRAORDINARY_PARTICIPANT_MAIL_LISTS',
        'SETTING_REUNIST_MEMBER_MAIL_LISTS' => 'REUNIST_MAIL_LISTS',
        'SETTING_HONORARY_MEMBER_MAIL_LISTS' => 'HONORARY_MEMBER_MAIL_LISTS',
        'SETTING_MERIT_MEMBER_MAIL_LISTS' => 'MEMBER_OF_SERVICE_MAIL_LISTS',
        'SETTING_TRAINER_MEMBER_MAIL_LISTS' => 'TRAINER_MAIL_LISTS',
        'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS' => 'RELATIONSHIP_MAIL_LISTS',
    ];

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('kind_of_member', 'member_type');
        });
        foreach (self::MEMBER_TYPES as $from => $to) {
            DB::table('users')->where('member_type', $from)->update(['member_type' => $to]);
        }

        Schema::table('user_event_log_entries', function (Blueprint $table) {
            $table->dropForeign('user_event_log_entries_user_id_foreign');
            $table->dropIndex('user_event_log_entries_user_id_foreign');
            $table->renameColumn('event_type', 'type');
            $table->renameColumn('event_details', 'details');
        });
        Schema::rename('user_event_log_entries', 'member_log_entries');
        Schema::table('member_log_entries', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        foreach (self::LOG_TYPES as $from => $to) {
            DB::table('member_log_entries')->where('type', $from)->update(['type' => $to]);
        }

        foreach (self::SETTINGS as $from => $to) {
            DB::table('settings')->where('name', $from)->update(['name' => $to]);
        }
    }

    public function down(): void
    {
        foreach (self::SETTINGS as $from => $to) {
            DB::table('settings')->where('name', $to)->update(['name' => $from]);
        }

        foreach (self::LOG_TYPES as $from => $to) {
            DB::table('member_log_entries')->where('type', $to)->update(['type' => $from]);
        }
        Schema::table('member_log_entries', function (Blueprint $table) {
            $table->dropForeign('member_log_entries_user_id_foreign');
            $table->dropIndex('member_log_entries_user_id_foreign');
        });
        Schema::rename('member_log_entries', 'user_event_log_entries');
        Schema::table('user_event_log_entries', function (Blueprint $table) {
            $table->renameColumn('type', 'event_type');
            $table->renameColumn('details', 'event_details');
            $table->foreign('user_id')->references('id')->on('users');
        });

        foreach (self::MEMBER_TYPES as $from => $to) {
            DB::table('users')->where('member_type', $to)->update(['member_type' => $from]);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('member_type', 'kind_of_member');
        });
    }
};
