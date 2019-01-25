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
        $photo->link = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/7.jpeg";
        $photo->thumbnail = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/7_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/8.jpeg";
        $photo->thumbnail = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/8_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/9.jpeg";
        $photo->thumbnail = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/9_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/10.jpeg";
        $photo->thumbnail = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/10_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/11.jpeg";
        $photo->thumbnail = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/11_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();

        $photo = new \App\Photo();
        $photo->link = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/12.jpeg";
        $photo->thumbnail = "https://esac-ic.s3.eu-central-1.amazonaws.com/photos/WEBI_3099/12_thumbnail.jpeg";
        $photo->width=100;
        $photo->height=100;
        $photo->photo_album()->associate(1);
        $photo->user()->associate(1);
        $photo->save();
    }
}