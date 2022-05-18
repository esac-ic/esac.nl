<?php

namespace Database\Seeders;

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
        $photo->link = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/234.jpeg";
        $photo->thumbnail = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/234_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/235.jpeg";
        $photo->thumbnail = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/235_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/236.jpeg";
        $photo->thumbnail = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/236_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/237.jpeg";
        $photo->thumbnail = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/237_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/238.jpeg";
        $photo->thumbnail = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/238_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/239.jpeg";
        $photo->thumbnail = "https://f002.backblazeb2.com/file/ESAC-Photos/photos/Seeder_photos/239_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();
    }
}