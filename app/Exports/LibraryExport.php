<?php

namespace App\Exports;

use APP\Repositories\BookRepository;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class LibraryExport implements FromCollection, WithTitle, ShouldAutoSize
{
    
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $books = $this->bookRepository->all(['*']);
        $exportData = [];
        foreach($books as $book) {
            //possible here to do some data filtering and manipulation etc, but isn't necesary at time of writing
            $data = $book->toArray();
            array_push($exportData, $data);
        }
        return new Collection($exportData);
    }
    
    public function title(): String
    {
        return 'Books';
    }
}
