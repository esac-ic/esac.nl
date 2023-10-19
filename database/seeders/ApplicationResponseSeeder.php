<?php

namespace Database\Seeders;

use App\Models\ApplicationForm\ApplicationResponse;
use Illuminate\Database\Seeder;

class ApplicationResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $applicationResponse = new ApplicationResponse();
        $applicationResponse->agenda_id = 1;
        $applicationResponse->inschrijf_form_id = 1;
        $applicationResponse->user_id = 1;
        $applicationResponse->save();
    }
}
