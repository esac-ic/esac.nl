<?php

namespace App\Exports;

use APP\Repositories\BookRepository;
// use DebugBar\DebugBar;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Log;

class LibraryExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
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
            $book->makeHidden('updated_at', 'deleted_at', 'created_at');
            $data = $book->toArray();
            
            array_push($exportData, $data);
        }
        return new Collection($exportData);
    }
    
    public function title(): String
    {
        return 'Books';
    }
    
    public function headings(): array
    {
        return [
            '#',
            'Title',
            'Year',
            'Type',
            'Country',
            'Code',
        ];
    }
}
