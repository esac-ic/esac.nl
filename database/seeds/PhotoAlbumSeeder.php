<?php

use Illuminate\Database\Seeder;

class PhotoAlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $photoAlbum = new \App\PhotoAlbum();
        $photoAlbum->title = "WEBI 2099";
        $photoAlbum->description = "Webi 2099 is nog niet geweest maar eh hier zijn de fotos";
        $photoAlbum->user()->associate(1);
        $photoAlbum->save();  
    }
}