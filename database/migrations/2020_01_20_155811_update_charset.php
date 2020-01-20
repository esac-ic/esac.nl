<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCharset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('ALTER TABLE `texts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE `zekerings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE `books` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::unprepared('ALTER TABLE `photo_albums` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('ALTER TABLE `texts` CONVERT TO CHARACTER SET utf8');
        DB::unprepared('ALTER TABLE `zekerings` CONVERT TO CHARACTER SET utf8');
        DB::unprepared('ALTER TABLE `books` CONVERT TO CHARACTER SET utf8');
        DB::unprepared('ALTER TABLE `photo_albums` CONVERT TO CHARACTER SET utf8');
    }
}
