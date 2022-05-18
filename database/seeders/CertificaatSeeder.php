<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CertificaatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $text = new \App\Text(['NL_text' => 'Outdoor voorklimmen', 'EN_text' => 'Outdoor lead climbing ']);
        $text->save();
        $certificat  = new \App\certificate(['name' => $text->id, 'abbreviation' => "OV"]);
        $certificat->save();

        $text = new \App\Text(['NL_text' => 'Indoor voorklimmen', 'EN_text' => 'Indoor lead climbing ']);
        $text->save();
        $certificat  = new \App\certificate(['name' => $text->id, 'abbreviation' => "IV"]);
        $certificat->save();
    }
}
