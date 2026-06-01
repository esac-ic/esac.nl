<?php

namespace Database\Seeders;

use App\Models\Rol;
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
        $rol = new Rol(['name' => 'Administrator']);
        $rol->save();
        $rol = new Rol(['name' => 'Content administrator']);
        $rol->save();
        $rol = new Rol(['name' => 'Activity administrator']);
        $rol->save();
        $rol = new Rol(['name' => 'Certificate administrator']);
        $rol->save();
    }
}
