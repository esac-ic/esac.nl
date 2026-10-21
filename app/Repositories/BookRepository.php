<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository implements IRepository
{
    public function create(array $data): Book
    {
        $book = new Book($data);
        $book->save();

        return $book;
    }

    public function update($id, array $data)
    {
        $book = $this->find($id);
        $book->update($data);
        return $book;
    }

    public function delete($id): void
    {
        $book = $this->find($id);
        $book->delete();
    }

    public function find($id, $columns = ['*'])
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return Book::where($field, '=', $value)->get($columns);
    }

    public function all($columns = ['*'])
    {
        return Book::all();
    }
}
