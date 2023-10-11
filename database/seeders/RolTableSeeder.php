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
        $rol = new \App\Rol(['name' => 'Administrator']);
        $rol->save();
        $rol = new \App\Rol(['name' => 'Content administrator']);
        $rol->save();
        $rol = new \App\Rol(['name' => 'Activity administrator']);
        $rol->save();
        $rol = new \App\Rol(['name' => 'Certificate administrator']);
        $rol->save();
    }
}
