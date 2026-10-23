<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Stores a member's roles as a list on the user, now that the AdminRole enum defines the roles,
 * and drops the roles table with its pivot. Roles that the enum doesn't know granted nothing and are dropped.
 */
return new class extends Migration
{
    private const array ROLES = [
        1 => 'administrator',
        2 => 'content_administrator',
        3 => 'event_administrator',
        4 => 'certificate_administrator',
    ];

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('roles')->default(new Expression('(JSON_ARRAY())'))->after('member_type');
        });

        DB::table('role_user')->whereIn('role_id', array_keys(self::ROLES))->orderBy('role_id')->get()->groupBy('user_id')
            ->each(fn ($rows, $userId) => DB::table('users')->where('id', $userId)->update([
                'roles' => json_encode($rows->map(fn (object $row) => self::ROLES[$row->role_id])->values()),
            ]));

        Schema::drop('role_user');
        Schema::drop('roles');
    }

    public function down(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->primary(['role_id', 'user_id']);
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        foreach (self::ROLES as $id => $role) {
            DB::table('roles')->insert(['id' => $id, 'name' => str($role)->replace('_', ' ')->ucfirst()]);
        }
        foreach (DB::table('users')->get(['id', 'roles']) as $user) {
            foreach (json_decode($user->roles) as $role) {
                DB::table('role_user')->insert(['role_id' => array_search($role, self::ROLES), 'user_id' => $user->id]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};
