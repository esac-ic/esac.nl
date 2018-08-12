<?php

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
        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Zuid', 'EN_text' => 'climbing Neoliet South']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Zuid text', 'EN_text' => 'climbing at Neoliet South text']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Zuid', 'EN_text' => 'climbing at Neoliet South']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(5)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(5)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 2;
        $agendaItem->application_form_id = 1;
        $agendaItem->save();

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(12)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(12)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(7)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(7)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();$title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(14)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(14)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();$title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(21)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(21)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(21)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(21)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();$title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(28)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(28)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();$title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(35)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(35)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(42)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(42)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(49)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(49)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

        $title = new \App\Text(['NL_text' => 'Klimmen Neoliet Noord', 'EN_text' => 'climbing Neoliet North']);
        $title->save();
        $text = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $text->save();
        $shortDescription = new \App\Text(['NL_text' => 'Klimmen bij Neoliet Noord', 'EN_text' => 'climbing at Neoliet North']);
        $shortDescription->save();

        $agendaItem = new \App\AgendaItem();
        $agendaItem->startDate = \Carbon\Carbon::now()->addDays(56)->addHours(12);
        $agendaItem->endDate = \Carbon\Carbon::now()->addDays(56)->addHours(15);
        $agendaItem->subscription_endDate = \Carbon\Carbon::now()->addDays(2)->addHours(15);
        $agendaItem->title = $title->id;
        $agendaItem->text = $text->id;
        $agendaItem->shortDescription = $shortDescription->id;
        $agendaItem->image_url = "";
        $agendaItem->createdBy = 1;
        $agendaItem->category = 1;
        $agendaItem->application_form_id = 2;
        $agendaItem->save();

    }
}
