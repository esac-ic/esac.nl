<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AgendaItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agendaItemCategory = new \App\Models\AgendaItemCategory(['name' => 'Training']);
        $agendaItemCategory->save();
        $agendaItemCategory = new \App\Models\AgendaItemCategory(['name' => 'Climbing']);
        $agendaItemCategory->save();
        $agendaItemCategory = new \App\Models\AgendaItemCategory(['name' => 'Other activities']);
        $agendaItemCategory->save();
    }
}
