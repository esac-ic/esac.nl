<?php

use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $photo = new \App\Photo();
        $photo->link = "www.google.com";
        $photo->thumbnail()->associate(null);
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();
    }
}