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
        $photoAlbum->description = "Webi 2018 is nog niet geweest maar eh hier zijn de fotos";
        $photoAlbum->user()->associate(1);
        $photoAlbum->save(); 
        
        $photoAlbum = new \App\PhotoAlbum();
        $photoAlbum->title = "Waco Wandeling Febuari";
        $photoAlbum->description = "Leuke wandeling in de brabantse hei!";
        $photoAlbum->user()->associate(1);
        $photoAlbum->save();    
    }
}