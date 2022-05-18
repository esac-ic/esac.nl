<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $book = new \App\Book();
        $book->title = "Dit is een boek";
        $book->year = 2012;
        $book->country = "Nederland";
        $book->type = "Gidsje";
        $book->code = "A301";

        $book->save();
    }
}
