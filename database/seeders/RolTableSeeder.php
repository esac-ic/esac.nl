<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //add default data
        $text = new \App\Text(['NL_text' => 'Beheerder', 'EN_text' => 'Administrator']);
        $text->save();
        $rol  = new \App\Rol(['name' => $text->id]);
        $rol->save();
        $text = new \App\Text(['NL_text' => 'Content beheerder', 'EN_text' => 'Content administrator']);
        $text->save();
        $rol  = new \App\Rol(['name' => $text->id]);
        $rol->save();
        $text = new \App\Text(['NL_text' => 'Activiteit beheerder', 'EN_text' => 'Activity administrator']);
        $text->save();
        $rol  = new \App\Rol(['name' => $text->id]);
        $rol->save();
        $text = new \App\Text(['NL_text' => 'Certificaat beheerder', 'EN_text' => 'Certificate administrator']);
        $text->save();
        $rol  = new \App\Rol(['name' => $text->id]);
        $rol->save();
    }
}