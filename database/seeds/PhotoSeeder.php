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
        $photo->link = "photos/WEBI_2099/47.png";
        $photo->thumbnail = "photos/WEBI_2099/47_thumbnail.png";
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "photos/WEBI_2099/48.png";
        $photo->thumbnail = "photos/WEBI_2099/48_thumbnail.png";
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "photos/WEBI_2099/49.png";
        $photo->thumbnail = "photos/WEBI_2099/49_thumbnail.png";
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "photos/WEBI_2099/50.png";
        $photo->thumbnail = "photos/WEBI_2099/50_thumbnail.png";
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "photos/WEBI_2099/51.png";
        $photo->thumbnail = "photos/WEBI_2099/51_thumbnail.png";
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();
    }
}