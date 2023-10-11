<?php

namespace Database\Seeders;

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
    }
}
