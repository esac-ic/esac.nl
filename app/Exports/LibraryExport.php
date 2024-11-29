<?php

namespace App\Exports;

use APP\Repositories\BookRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LibraryExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithMapping
{
    
    private BookRepository $bookRepository;
    
    /**
     * All properties to be included in the export. Maps model property to header label.
     * Note: the keys should be the same as the model attributes
     */
    private const PROPERTY_LABELS = [
        "id" => "#",
        "title" => "Title",
        "year" => "Year",
        "type" => "Type",
        "country" => "Country",
        "code" => "Code",
    ];

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->bookRepository->all(["*"]);
    }
    
    public function title(): String
    {
        return 'Books';
    }
    
    public function headings(): array
    {
        return array_values(LibraryExport::PROPERTY_LABELS);
    }
    
    /**
     * Map a single book (row) to an array of cell values.
     * Note: The order of these values must match the order of the headings
     * 
     * @param \APP\Book $book
     * @return array
     */
    public function map($book): array
    {
        $properties = array_keys(LibraryExport::PROPERTY_LABELS);
        return $book->only($properties);
    }
}
