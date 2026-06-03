<?php

namespace Database\Seeders;

use App\Models\AgendaItemCategory;
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
        $agendaItemCategory = new AgendaItemCategory(['name' => 'Training']);
        $agendaItemCategory->save();
        $agendaItemCategory = new AgendaItemCategory(['name' => 'Climbing']);
        $agendaItemCategory->save();
        $agendaItemCategory = new AgendaItemCategory(['name' => 'Other activities']);
        $agendaItemCategory->save();
    }
}
