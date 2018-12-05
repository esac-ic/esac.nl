<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\repositories\PhotoAlbumRepository;
use App\repositories\RepositorieFactory;

class PhotoAlbumController extends Controller
{
    private $_PhotoAlbumRepository;
    private $_PhotoRepository;

    public function __construct(RepositorieFactory $repositorieFactory){
        $this->_PhotoAlbumRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$PHOTOALBUMREPOKEY);
        $this->_PhotoRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$PHOTOREPOKEY);
        $this->middleware('auth');
    }

    public function index()
    {
        $photoAlbums = $this->_PhotoAlbumRepository->all();
        foreach($photoAlbums as $photoalbum){
              $photoalbum->description = str_replace("\r\n","<br>", $photoalbum->description);
        }
        $curPageName = trans('front-end/photo.pagetitle');
        return view('front-end.photo_album_list', compact('curPageName', 'photoAlbums'));
    }
}
