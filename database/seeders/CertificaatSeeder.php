<?php

namespace Database\Seeders;

use App\Certificate;
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
        $certificat = new Certificate(['name' => 'Outdoor lead climbing', 'abbreviation' => "OV"]);
        $certificat->save();

        $certificat = new Certificate(['name' => 'Indoor lead climbing', 'abbreviation' => "IV"]);
        $certificat->save();
    }
}
