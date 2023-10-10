<?php

namespace App\Repositories;

use App\Book;

class BookRepository implements IRepository
{
    public function create(array $data)
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

    public function delete($id)
    {
        $book = $this->find($id);
        $book->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Book::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Book::all();
    }
}
