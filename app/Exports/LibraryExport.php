<?php

namespace App\Exports;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Support\Collection;

class LibraryExport extends SpreadsheetExport
{
    
    /**
     * All properties to be included in the export. Maps model property to header label.
     * Note: the keys should be the same as the model attributes
     */
    private const array PROPERTY_LABELS = [
        "id" => "#",
        "title" => "Title",
        "year" => "Year",
        "type" => "Type",
        "country" => "Country",
        "code" => "Code",
    ];

    public function __construct(private readonly BookRepository $bookRepository)
    {
    }
    
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->bookRepository->all(["*"]);
    }
    
    public function title(): string
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
     * @param Book $book
     * @return array
     */
    public function map(mixed $book): array
    {
        $properties = array_keys(LibraryExport::PROPERTY_LABELS);
        return $book->only($properties);
    }
}
