<?php

namespace Database\Seeders;

use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
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
        $applicationForm = new ApplicationForm();
        $applicationForm->name = 'Aplication form climbing weekend';
        $applicationForm->save();

        $row = new ApplicationFormRow();
        $row->name = 'Car available';
        $row->type = "Checkbox";
        $row->required = 1;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        $row = new ApplicationFormRow();
        $row->name = 'Tent available';
        $row->type = "Number";
        $row->required = 1;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        $row = new ApplicationFormRow();
        $row->name = 'Food reqeusts';
        $row->type = "Text";
        $row->required = 0;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        //second application form
        $applicationForm = new ApplicationForm();
        $applicationForm->name = 'Aplication form activity';
        $applicationForm->save();
        $row = new ApplicationFormRow();
        $row->name = 'Food reqeusts';
        $row->type = "Text";
        $row->required = 0;
        $row->application_form_id = $applicationForm->id;
        $row->save();
    }
}
