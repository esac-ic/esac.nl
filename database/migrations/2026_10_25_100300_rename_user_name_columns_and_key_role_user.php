<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Renames firstname and lastname to first_name and last_name, and gives role_user a composite primary key and
 * foreign keys. Duplicate rows and rows of deleted roles or users are removed first, as they grant nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('firstname', 'first_name');
            $table->renameColumn('lastname', 'last_name');
        });

        DB::transaction(function () {
            DB::table('role_user')->whereNotIn('role_id', DB::table('roles')->select('id'))->delete();
            DB::table('role_user')->whereNotIn('user_id', DB::table('users')->select('id'))->delete();
            $rows = DB::table('role_user')->distinct()->get(['role_id', 'user_id']);
            DB::table('role_user')->delete();
            DB::table('role_user')->insert($rows->map(fn (object $row) => (array) $row)->all());
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->primary(['role_id', 'user_id']);
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign('role_user_role_id_foreign');
            $table->dropForeign('role_user_user_id_foreign');
            $table->dropPrimary();
            $table->dropIndex('role_user_user_id_foreign');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'firstname');
            $table->renameColumn('last_name', 'lastname');
        });
    }
};
