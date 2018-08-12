<?php

use Illuminate\Database\Seeder;

class ApplicationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $text = new \App\Text(['NL_text' => 'Inschrijf formulier klim weekend ', 'EN_text' => 'Aplication form climbing weekend']);
        $text->save();

        $applicationForm = new \App\ApplicationForm(['name'  =>  $text->id]);
        $applicationForm->save();

        $text = new \App\Text(['NL_text' => 'Auto beschikbaar', 'EN_text' => 'Car available']);
        $text->save();
        $row = new \App\ApplicationFormRow();
        $row->name = $text->id;
        $row->type = "Checkbox";
        $row->required = 1;
        $row->application_form_id = $applicationForm->id;
        $row->save();


        $text = new \App\Text(['NL_text' => 'Tent beschikbaar', 'EN_text' => 'Tent available']);
        $text->save();
        $row = new \App\ApplicationFormRow();
        $row->name = $text->id;
        $row->type = "Cijfer";
        $row->required = 1;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        $text = new \App\Text(['NL_text' => 'Eet wensen', 'EN_text' => 'Food reqeusts']);
        $text->save();
        $row = new \App\ApplicationFormRow();
        $row->name = $text->id;
        $row->type = "Text";
        $row->required = 0;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        //second application form
        $text = new \App\Text(['NL_text' => 'Inschrijf formulier activiteit', 'EN_text' => 'Aplication form activitie']);
        $text->save();

        $applicationForm = new \App\ApplicationForm(['name'  =>  $text->id]);
        $applicationForm->save();

        $text = new \App\Text(['NL_text' => 'Eet wensen', 'EN_text' => 'Food reqeusts']);
        $text->save();
        $row = new \App\ApplicationFormRow();
        $row->name = $text->id;
        $row->type = "Text";
        $row->required = 0;
        $row->application_form_id = $applicationForm->id;
        $row->save();
    }
}
