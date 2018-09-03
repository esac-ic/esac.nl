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
        $photoAlbum->title = "WEBI 2018";
        $photoAlbum->user()->associate(1);
        $photoAlbum->save();    
    }
}