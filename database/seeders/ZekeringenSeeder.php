<?php

namespace Database\Seeders;

use App\Models\Zekering;
use Illuminate\Database\Seeder;

class ZekeringenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // inserting test data
        $zekering = new Zekering;
        $zekering->text = 'dit is een dummy tekst voor een zekering';
        $zekering->created_by = 1;
        $zekering->score = 1;
        $zekering->parent_id = 1;

        // add zekering(en)
        $zekering->save();

        $zekering = new Zekering;
        $zekering->text = 'dit is een dummy tekst 2 voor een zekering';
        $zekering->created_by = 1;
        $zekering->score = 1;
        $zekering->parent_id = 2;

        // add zekering(en)
        $zekering->save();

        $zekering = new Zekering;
        $zekering->text = 'dit is een dummy tekst 2 voor een zekering met een parent';
        $zekering->created_by = 1;
        $zekering->score = 1;
        $zekering->parent_id = 2;

        // add zekering(en)
        $zekering->save();
    }
}
