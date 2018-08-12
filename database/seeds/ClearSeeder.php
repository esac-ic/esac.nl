<?php

use Illuminate\Database\Seeder;


//emptys data from database
class ClearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Certificate::getQuery()->delete();
        \App\MenuItem::getQuery()->delete();
        \App\Rol::getQuery()->delete();
        \App\AgendaItem::getQuery()->delete();
        \App\AgendaItemCategorie::getQuery()->delete();
        \App\ApplicationFormRow::getQuery()->delete();
        \App\ApplicationForm::getQuery()->delete();
        \App\NewsItem::getQuery()->delete();
        \App\User::getQuery()->delete();
        \App\Text::getQuery()->delete();
        \App\Zekering::getQuery()->delete();
        \App\Book::getQuery()->delete();
    }
}
