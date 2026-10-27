<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // The dev setup seeds on every start; only an empty database gets the dev data
        if (User::query()->exists()) {
            $this->command?->info('The database already has users, skipping the seed data (migrate:fresh --seed reseeds it)');
            return;
        }

        $this->call(ClearSeeder::class);
        $this->call(RolTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MenuItemTableSeeder::class);
        $this->call(CertificaatSeeder::class);
        $this->call(AgendaItemCategorySeeder::class);
        $this->call(ApplicationFormSeeder::class);
        $this->call(AgendaItemSeeder::class);
        $this->call(NewsItemSeeder::class);
        $this->call(ZekeringenSeeder::class);
        $this->call(ApplicationResponseSeeder::class);
        $this->call(BooksTableSeeder::class);
        $this->call(UserEventLogEntrySeeder::class);
    }
}
