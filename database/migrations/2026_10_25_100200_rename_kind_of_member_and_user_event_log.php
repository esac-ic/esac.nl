<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames the kind of member to member type (with the participant names of the statutes), the user event log to the member log
 * with snake_case log types, and names the settings in snake_case.
 */
return new class extends Migration
{
    /**
     * The logged event class names and the log types that replace them.
     */
    private const array LOG_TYPES = [
        'MemberKindChanged' => 'member_type_changed',
        'MemberBecameOldMember' => 'membership_ended',
        'OldMemberBecameMember' => 'membership_restored',
        'PendingUserCreated' => 'member_signed_up',
        'PendingUserApproved' => 'sign_up_approved',
        'PendingUserRemoved' => 'sign_up_rejected',
    ];

    private const array MEMBER_TYPES = [
        'extraordinary_member' => 'extraordinary_participant',
        'member_of_merit' => 'member_of_service',
    ];

    private const array SETTINGS = [
        'SETTING_BLOCKED_EMAIL_DOMAINS' => 'blocked_email_domains',
        'SETTING_KILLSWITCH' => 'killswitch',
        'SETTING_FOOTER_PHONE_NR' => 'footer_phone_number',
        'SETTING_NORMAL_MEMBER_MAIL_LISTS' => 'member_mail_lists',
        'SETTING_EXTRAORDINARY_MEMBER_MAIL_LISTS' => 'extraordinary_participant_mail_lists',
        'SETTING_REUNIST_MEMBER_MAIL_LISTS' => 'reunist_mail_lists',
        'SETTING_HONORARY_MEMBER_MAIL_LISTS' => 'honorary_member_mail_lists',
        'SETTING_MERIT_MEMBER_MAIL_LISTS' => 'member_of_service_mail_lists',
        'SETTING_TRAINER_MEMBER_MAIL_LISTS' => 'trainer_mail_lists',
        'SETTING_RELATIONSHIP_MEMBER_MAIL_LISTS' => 'relationship_mail_lists',
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
            // An entry outlives the user it's about, such as a rejected sign-up.
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
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
