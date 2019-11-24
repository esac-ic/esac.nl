<?php

namespace App\Http\Controllers;

use App\repositories\RepositorieFactory;
use App\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class LibraryController extends Controller
{

    private $_bookRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.Config::get('constants.Administrator'));
        $this->_bookRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$BOOKREPOKEY);
    }

    //gives the book views
    public function index(){
        $books = $this->_bookRepository->all(array('title','id','type','code','year','country'));
        return view('beheer.book.index', compact('books'));
    }

    //show create screen
    public function create(){
        $fields = ['title' => trans('book.add'),
            'method' => 'POST',
            'url' => '/books',];
        $book = null;
        return view('beheer.book.create_edit', compact('fields','book'));
    }

    //store book
    public function store(Request $request){
        $this->validateInput($request);

        $this->_bookRepository->create($request->all());

        Session::flash("message",trans('book.added'));
        return redirect('/books');
    }

    public function show(Request $request, Book $book){
        return view('beheer.book.show', compact('book'));
    }

    //show edit screen
    public function edit(Request $request, Book $book){
        $fields = ['title' => trans('book.edit'),
            'method' => 'PATCH',
            'url' => '/books/'. $book->id];

        return view('beheer.book.create_edit', compact('fields','book'));
    }

    //update book
    public function update(Request $request, Book $book){
        $this->validateInput($request);

        $this->_bookRepository->update($book->id,$request->all());

        Session::flash("message",trans('book.edited'));
        return redirect('/books');
    }

    public function destroy(Request $request, Book $book){
        $this->_bookRepository->delete($book->id);

        Session::flash("message",trans('book.deleted'));
        return redirect('/books');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'year' => 'integer',
            'type' => 'required',
            'country' => 'required',
            'code' => 'required',
        ]);
    }
}
