<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AgendaItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(5)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(5)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet South';
        $agendaItem->text = 'Climbing at Neoliet South text';
        $agendaItem->shortDescription = 'Climbing at Neoliet South';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 2;
        $agendaItem->application_form_id = 1;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(12)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(12)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(7)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(7)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(14)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(14)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(21)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(21)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(21)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(21)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(28)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(28)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(35)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(35)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(42)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(42)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(49)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(49)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = Carbon::now()->addDays(56)->addHours(12);
        $agendaItem->endDate = Carbon::now()->addDays(56)->addHours(15);
        $agendaItem->subscription_endDate = Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = 'Climbing Neoliet North';
        $agendaItem->text = 'Climbing at Neoliet North text';
        $agendaItem->shortDescription = 'Climbing at Neoliet North';
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

    }
}
