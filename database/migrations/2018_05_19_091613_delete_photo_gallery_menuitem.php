<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletePhotoGalleryMenuitem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menuItem = \App\MenuItem::query()
            ->where('urlName','=','photoGallery')
            ->first();
        if($menuItem != null){
            $menuItem->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $menuItem = \App\MenuItem::query()
            ->where('urlName','=','photoGallery')
            ->first();
        if($menuItem != null){
            $menuItem->deleted_at = null;
            $menuItem->save();
        }
    }
}
