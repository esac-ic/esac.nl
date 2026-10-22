<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Exports\LibraryExport;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Session;

class LibraryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('authorize:' . Role::Administrator->value),
        ];
    }

    public function index(): View
    {
        $books = Book::all(['title', 'id', 'type', 'code', 'year', 'country']);
        return view('beheer.book.index', compact('books'));
    }

    public function create(): View
    {
        $fields = [
            'title' => 'Add a book',
            'method' => 'POST',
            'url' => route('books.store'),
        ];
        $book = null;
        return view('beheer.book.create_edit', compact('fields', 'book'));
    }

    public function store(Request $request)
    {
        Book::create($this->validateInput($request));
        Session::flash("message", 'Book added');
        return to_route('books.index');
    }

    public function show(Book $book): View
    {
        return view('beheer.book.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $fields = [
            'title' => 'Edit book',
            'method' => 'PATCH',
            'url' => route('books.update', $book->id),
        ];
        return view('beheer.book.create_edit', compact('fields', 'book'));
    }

    public function update(Request $request, Book $book)
    {
        $book->update($this->validateInput($request));
        Session::flash("message", 'Book edited');
        return to_route('books.index');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        Session::flash("message", 'Book removed');
        return to_route('books.index');
    }
    
    public function exportLibrary(LibraryExport $libraryExport)
    {
        return $libraryExport->download('Library.xlsx');
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'title' => 'required|max:255',
            'year' => 'integer',
            'type' => 'required|max:255',
            'country' => 'required|max:255',
            'code' => 'required|max:255',
        ]);
    }
}
