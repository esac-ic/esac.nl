<?php

namespace App\Exports;

use App\Models\Book;
use Illuminate\Support\Collection;

class BooksExport extends SpreadsheetExport
{
    /**
     * The headings of the exported attributes, by attribute.
     */
    private const array COLUMNS = [
        'id' => '#',
        'title' => 'Title',
        'year' => 'Year',
        'type' => 'Type',
        'country' => 'Country',
        'code' => 'Code',
    ];

    public function collection(): Collection
    {
        return Book::all();
    }

    public function title(): string
    {
        return 'Books';
    }

    public function headings(): array
    {
        return array_values(self::COLUMNS);
    }

    /**
     * @param  Book  $book
     */
    public function map(mixed $book): array
    {
        return $book->only(array_keys(self::COLUMNS));
    }
}
