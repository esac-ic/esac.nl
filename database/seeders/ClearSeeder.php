<?php

namespace Database\Seeders;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\Book;
use App\Certificate;
use App\MenuItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\NewsItem;
use App\Rol;
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
        Zekering::getQuery()->delete();
        Certificate::getQuery()->delete();
        MenuItem::getQuery()->delete();
        AgendaItem::getQuery()->delete();
        AgendaItemCategory::getQuery()->delete();
        ApplicationFormRow::getQuery()->delete();
        NewsItem::getQuery()->delete();
        Book::getQuery()->delete();
        ApplicationForm::getQuery()->delete();
        User::getQuery()->delete();
        Rol::getQuery()->delete();
    }
}
