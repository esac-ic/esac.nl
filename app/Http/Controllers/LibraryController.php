<?php

namespace App\Http\Controllers;

use App\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

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
        $books = $this->_bookRepository->all(['title','id','type','code','year','country']);
        return view('beheer.book.index', compact('books'));
    }

    public function create()
    {
        $fields = [
            'title' => trans('book.add'),
            'method' => 'POST',
            'url' => route('books.store')
        ];
        return view('beheer.book.create_edit', compact('fields'));
    }

    public function store(Request $request)
    {
        $this->validateInput($request);
        $this->_bookRepository->create($request->all());
        Session::flash("message", trans('book.added'));
        return redirect()->route('books.index');
    }

    public function show(Book $book)
    {
        return view('beheer.book.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $fields = [
            'title' => trans('book.edit'),
            'method' => 'PATCH',
            'url' => route('books.update', $book->id)
        ];
        return view('beheer.book.create_edit', compact('fields', 'book'));
    }

    public function update(Request $request, Book $book)
    {
        $this->validateInput($request);
        $this->_bookRepository->update($book->id, $request->all());
        Session::flash("message", trans('book.edited'));
        return redirect()->route('books.index');
    }

    public function destroy(Book $book)
    {
        $this->_bookRepository->delete($book->id);
        Session::flash("message", trans('book.deleted'));
        return redirect()->route('books.index');
    }

    private function validateInput(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'year' => 'integer',
            'type' => 'required',
            'country' => 'required',
            'code' => 'required',
        ]);
    }
}
