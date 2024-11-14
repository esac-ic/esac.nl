<?php

namespace App\Http\Controllers;

use App\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LibraryExport;

class LibraryController extends Controller
{
    private $_bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->middleware('auth');
        $this->middleware('authorize:' . Config::get('constants.Administrator'));

        $this->_bookRepository = $bookRepository;
    }

    public function index()
    {
        $books = $this->_bookRepository->all(['title', 'id', 'type', 'code', 'year', 'country']);
        return view('beheer.book.index', compact('books'));
    }

    public function create()
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

    public function show(Book $book)
    {
        return view('beheer.book.show', compact('book'));
    }

    public function edit(Book $book)
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
        return Excel::download($libraryExport, 'Library' . '.xlsx');
    }

    private function validateInput(Request $request)
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
