<?php

namespace Database\Seeders;

use App\Enums\FormRowType;
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
        $applicationForm = new ApplicationForm;
        $applicationForm->name = 'Application form climbing weekend';
        $applicationForm->save();

        $row = new ApplicationFormRow;
        $row->name = 'Car available';
        $row->type = FormRowType::CheckBox;
        $row->required = 1;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        $row = new ApplicationFormRow;
        $row->name = 'Tent available';
        $row->type = FormRowType::Number;
        $row->required = 1;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        $row = new ApplicationFormRow;
        $row->name = 'Food requests';
        $row->type = FormRowType::Text;
        $row->required = 0;
        $row->application_form_id = $applicationForm->id;
        $row->save();

        // second application form
        $applicationForm = new ApplicationForm;
        $applicationForm->name = 'Application form activity';
        $applicationForm->save();
        $row = new ApplicationFormRow;
        $row->name = 'Food requests';
        $row->type = FormRowType::Text;
        $row->required = 0;
        $row->application_form_id = $applicationForm->id;
        $row->save();
    }
}
