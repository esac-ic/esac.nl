<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateCreatedByFieldAuthorField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newsItems = \App\NewsItem::all();

        foreach ($newsItems as $newsItem){
            $user = \App\User::find($newsItem->createdBy);
            if($user !== null){
                $newsItem->author = $user->getName();
                $newsItem->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
