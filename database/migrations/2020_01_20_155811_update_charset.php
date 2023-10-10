<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UpdateCharset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('ALTER DATABASE ' . env('DB_DATABASE') . ' CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE texts CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE zekerings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE books CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE photo_albums CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('ALTER TABLE texts CONVERT TO CHARACTER SET utf8');
        DB::unprepared('ALTER TABLE zekerings CONVERT TO CHARACTER SET utf8');
        DB::unprepared('ALTER TABLE books CONVERT TO CHARACTER SET utf8');
        if (Schema::hasTable('photo_albums')) {
            DB::unprepared('ALTER TABLE photo_albums CONVERT TO CHARACTER SET utf8');
        }
    }
}
