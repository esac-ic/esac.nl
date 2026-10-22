<?php

namespace App\Http\Controllers;

use App\Exports\LibraryExport;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibraryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('can:manage-library'),
        ];
    }

    public function index(): View
    {
        return view('beheer.book.index', [
            'books' => Book::all(['title', 'id', 'type', 'code', 'year', 'country']),
        ]);
    }

    public function create(): View
    {
        return view('beheer.book.create_edit', [
            'book' => null,
            'fields' => [
                'title' => 'Add a book',
                'method' => 'POST',
                'url' => route('books.store'),
            ],
        ]);
    }

    public function store(BookRequest $request): RedirectResponse
    {
        Book::create($request->validated());

        return to_route('books.index')->with('message', 'Book added');
    }

    public function show(Book $book): View
    {
        return view('beheer.book.show', ['book' => $book]);
    }

    public function edit(Book $book): View
    {
        return view('beheer.book.create_edit', [
            'book' => $book,
            'fields' => [
                'title' => 'Edit book',
                'method' => 'PATCH',
                'url' => route('books.update', $book),
            ],
        ]);
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $book->update($request->validated());

        return to_route('books.index')->with('message', 'Book edited');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return to_route('books.index')->with('message', 'Book removed');
    }

    public function exportLibrary(LibraryExport $libraryExport): StreamedResponse
    {
        return $libraryExport->download('Library.xlsx');
    }
}
