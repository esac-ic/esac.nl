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
        $certificat  = new \App\certificate(['name' => 'Outdoor lead climbing', 'abbreviation' => "OV"]);
        $certificat->save();

        $certificat  = new \App\certificate(['name' => 'Indoor lead climbing', 'abbreviation' => "IV"]);
        $certificat->save();
    }
}
