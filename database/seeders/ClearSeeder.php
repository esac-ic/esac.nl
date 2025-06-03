<?php

namespace Database\Seeders;

use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\Rol;
use App\Models\User;
use App\Models\Zekering;
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
