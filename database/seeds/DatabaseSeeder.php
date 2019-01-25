<?php

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
        $this->call(newsItemSeeder::class);
        $this->call(zekeringenSeeder::class);
        $this->call(ApplicationResponseSeeder::class);
        $this->call(frontendreplacementSeeder::class);
        $this->call(BooksTableSeeder::class); 
        $this->call(PhotoAlbumSeeder::class);
        $this->call(PhotoSeeder::class);

        //        if you get a reflection error after adding a seeder run "composer dump-autoload"
    }
}
