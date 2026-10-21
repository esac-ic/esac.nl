<?php

namespace App\Http\Controllers;

use App\Exports\LibraryExport;
use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class LibraryController extends Controller
{
    public function __construct(private readonly BookRepository $_bookRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));
    }

    public function index(): View
    {
        $books = $this->_bookRepository->all(['title', 'id', 'type', 'code', 'year', 'country']);
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
        $this->validateInput($request);
        $this->_bookRepository->create($request->all());
        Session::flash("message", 'Book added');
        return redirect()->route('books.index');
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
        $this->validateInput($request);
        $this->_bookRepository->update($book->id, $request->all());
        Session::flash("message", 'Book edited');
        return redirect()->route('books.index');
    }

    public function destroy(Book $book)
    {
        $this->_bookRepository->delete($book->id);
        Session::flash("message", 'Book removed');
        return redirect()->route('books.index');
    }
    
    public function exportLibrary(LibraryExport $libraryExport)
    {
        return $libraryExport->download('Library.xlsx');
    }

    private function validateInput(Request $request): void
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'year' => 'integer',
            'type' => 'required|max:255',
            'country' => 'required|max:255',
            'code' => 'required|max:255',
        ]);
    }
}
