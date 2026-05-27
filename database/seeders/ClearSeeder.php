<?php

namespace Database\Seeders;

use App\CustomClasses\MailList\MailListFacade;
use App\Models\AgendaItem;
use App\Models\AgendaItemCategory;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\ApplicationForm\ApplicationFormRow;
use App\Models\Book;
use App\Models\Certificate;
use App\Models\MenuItem;
use App\Models\NewsItem;
use App\Models\Rol;
use App\Models\UserEventLogEntry;
use App\User;
use App\Zekering;
use Exception;
use Illuminate\Database\Seeder;

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
