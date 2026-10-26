<?php

namespace Database\Seeders;

use App\Models\HeaderSlide;
use App\Models\Sponsor;
use Illuminate\Database\Seeder;

/**
 * The header slides and sponsors the site started with; skipped once there are any.
 * On production, run once with `php artisan db:seed --class=WebsiteSeeder --force`.
 */
class WebsiteSeeder extends Seeder
{
    private const array HEADER_SLIDES = [
        ['header-img1.jpeg', 'Stunts and more', 'Our stunt committee organizes stunts such as abseiling, zip lining and climbing during events.', '/stunt'],
        ['header-img2.jpeg', 'Ski mountaineering introduction', 'An introductory ski mountaineering course is regularly organized during the winter week.', '/alpenwinter'],
        ['header-img3.jpeg', 'Climbing weekends in Belgium', 'Every year the ESAC organizes over 20 climbing weekends in Belgium, Germany or further afield.', '/buitenklimmen'],
        ['header-img4.jpeg', 'Climbing competition', 'The EPlaKKa is held every year to find out who is the best climber of the ESAC.', '/klimwedstrijden'],
        ['header-img5.jpeg', 'Ski in the Alps', 'During the carnival holiday the ESAC travels to the Alps for a week to enjoy the snow.', '/alpenwinter'],
        ['header-img7.jpeg', 'Mountaineering courses', 'The NSAC offers courses at various levels, with the goal of going on alpine trips on your own.', '/alpenzomer'],
        ['header-img8.jpeg', 'Mountaineering', 'Mountaineering is the king of mountain sports, where all its facets come together.', '/alpenzomer'],
        ['header-img9.jpeg', 'Fun!', 'During and after climbing there is of course also room for good food and social activities.', '/events'],
        ['header-img10.jpeg', 'Bouldering', 'Besides sport climbing, we also do a lot of bouldering, including in Fontainebleau.', '/events'],
    ];

    private const array SPONSORS = [
        ['ssc.png', 'Student Sports Centre Eindhoven', 'https://venusp.tue.nl/sci-cgi/index.opl'],
        ['boulder.png', 'Boulder Neoliet', 'https://boulderneoliet.nl/'],
        ['neoliet.webp', 'Neoliet', 'https://www.neoliet.nl'],
        ['klimwinkel.png', 'Klimwinkel', 'https://www.klimwinkel.nl'],
    ];

    public function run(): void
    {
        if (! HeaderSlide::query()->exists()) {
            foreach (self::HEADER_SLIDES as [$image, $heading, $message, $link]) {
                HeaderSlide::query()->create(['heading' => $heading, 'message' => $message, 'link' => $link])
                    ->addMedia(__DIR__.'/images/'.$image)->preservingOriginal()->toMediaCollection('image');
            }
        }

        if (! Sponsor::query()->exists()) {
            foreach (self::SPONSORS as [$logo, $name, $url]) {
                Sponsor::query()->create(['name' => $name, 'url' => $url])
                    ->addMedia(__DIR__.'/images/'.$logo)->preservingOriginal()->toMediaCollection('logo');
            }
        }
    }
}
