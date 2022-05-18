<?php

namespace Database\Seeders;

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
        //inserting test data
        $zekering = new \App\Zekering();
        $zekering->text = "dit is een dummy tekst voor een zekering";
        $zekering->createdBy = 1;
        $zekering->score = 1;
        $zekering->parent_id = 1;
        $zekering->has_parent = 0;

        //add zekering(en)
        $zekering->save();

        $zekering = new \App\Zekering();
        $zekering->text = "dit is een dummy tekst 2 voor een zekering";
        $zekering->createdBy = 1;
        $zekering->score = 1;
        $zekering->parent_id = 2;
        $zekering->has_parent = 0;

        //add zekering(en)
        $zekering->save();

        $zekering = new \App\Zekering();
        $zekering->text = "dit is een dummy tekst 2 voor een zekering met een parent";
        $zekering->createdBy = 1;
        $zekering->score = 1;
        $zekering->parent_id = 2;
        $zekering->has_parent = 1;

        //add zekering(en)
        $zekering->save();
    }
}
