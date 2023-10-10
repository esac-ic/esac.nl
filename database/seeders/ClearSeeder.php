<?php

namespace Database\Seeders;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\Certificate;
use App\MenuItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\NewsItem;
use App\Rol;
use App\Text;
use App\User;
use App\Zekering;
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
        Certificate::getQuery()->delete();
        MenuItem::getQuery()->delete();
        Rol::getQuery()->delete();
        AgendaItem::getQuery()->delete();
        AgendaItemCategory::getQuery()->delete();
        ApplicationFormRow::getQuery()->delete();
        ApplicationForm::getQuery()->delete();
        NewsItem::getQuery()->delete();
        User::getQuery()->delete();
        Text::getQuery()->delete();
        Zekering::getQuery()->delete();
        \App\Book::getQuery()->delete();
    }
}
