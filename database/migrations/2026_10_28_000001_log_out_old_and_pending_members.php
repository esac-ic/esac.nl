<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Former and pending members can't log in, so their remember-me cookies and sessions stop working too
        $users = DB::table('users')->whereNotNull('membership_ended_on')->orWhereNotNull('pending_since');

        DB::table('sessions')->whereIn('user_id', $users->clone()->select('id'))->delete();
        $users->update(['remember_token' => null]);
    }
};
