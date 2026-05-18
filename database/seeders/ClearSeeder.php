<?php

namespace Database\Seeders;

use App\AgendaItem;
use App\AgendaItemCategory;
use App\Book;
use App\Certificate;
use App\CustomClasses\MailList\MailListFacade;
use App\MenuItem;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\UserEventLogEntry;
use App\NewsItem;
use App\Rol;
use App\User;
use App\Zekering;
use Illuminate\Database\Seeder;
Use Exception;

/** Empties data from database */
class ClearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(MailListFacade $mailListFacade)
    {
        // Clean out the mail lists (because without this each local re-seed keeps cluttering the mail lists with non-existent users)
        try {
            collect($mailListFacade->getAllMailListIds())->each($mailListFacade->deleteAllUsersFromMailList(...));
        } catch (Exception $e) {
            \Log::error($e->getMessage());
        }
        
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
        UserEventLogEntry::getQuery()->delete();
        
    }
}
