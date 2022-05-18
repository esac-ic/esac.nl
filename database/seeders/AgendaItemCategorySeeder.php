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
        $text = new \App\Text(['NL_text' => 'Training', 'EN_text' => 'Workout']);
        $text->save();
        $agendaItemCategory  = new \App\AgendaItemCategorie(['name' => $text->id]);
        $agendaItemCategory->save();
        $text = new \App\Text(['NL_text' => 'Klimmen', 'EN_text' => 'Climbing']);
        $text->save();
        $agendaItemCategory  = new \App\AgendaItemCategorie(['name' => $text->id]);
        $agendaItemCategory->save();
        $text = new \App\Text(['NL_text' => 'Overige activiteiten', 'EN_text' => 'Other activities']);
        $text->save();
        $agendaItemCategory  = new \App\AgendaItemCategorie(['name' => $text->id]);
        $agendaItemCategory->save();
    }
}
