<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PhotoAlbum;
use App\Repositories\PhotoAlbumRepository;
use App\Repositories\RepositorieFactory;
use App\MenuItem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;


class PhotoAlbumController extends Controller
{
    private $_PhotoAlbumRepository;
    private $_PhotoRepository;
    private $_MenuItemRepository;

    public function __construct(RepositorieFactory $repositorieFactory){
        $this->middleware('auth');
        $this->middleware('authorize:'.Config::get('constants.Administrator'));
        $this->_PhotoAlbumRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$PHOTOALBUMREPOKEY);
    }

    public function index(){
        $photoAlbums = $this->_PhotoAlbumRepository->all(array('title','date'));
        return view('beheer.photoAlbum.index', compact('photoAlbums'));
    }

    public function show(Request $request, PhotoAlbum $photoAlbum){
        return view('beheer.photoAlbum.show', compact('photoAlbum'));
    }

    public function edit(Request $request, PhotoAlbum $photoAlbum){
        $fields = ['title' => ('photoAlbum.edit'),
            'method' => 'PATCH',
            'url' => '/photoAlbums/'. $photoAlbum->id];

        return view('beheer.photoAlbum.create_edit', compact('fields','photoAlbum'));
    }

    public function update(Request $request, PhotoAlbum $photoAlbum){
        $this->validateInput($request);

        $this->_PhotoAlbumRepository->update($photoAlbum->id,$request->all());

        Session::flash("message",('photoAlbum.edited'));
        return redirect('/photoAlbums');
    }

    public function destroy(Request $request, PhotoAlbum $photoAlbum){
        $this->_PhotoAlbumRepository->delete($photoAlbum->id);

        Session::flash("message",('photoAlbum.deleted'));
        return redirect('/photoAlbums');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
        ]);
    }
}
