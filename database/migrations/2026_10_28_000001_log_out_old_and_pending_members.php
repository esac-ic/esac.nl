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
        // Old and pending members can't log in, so their remember-me cookies and sessions stop working too
        $users = DB::table('users')->whereNotNull('lid_af')->orWhereNotNull('pending_user');

        DB::table('sessions')->whereIn('user_id', $users->clone()->select('id'))->delete();
        $users->update(['remember_token' => null]);
    }
};
