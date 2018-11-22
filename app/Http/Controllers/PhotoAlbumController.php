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
        $albumsToFrontEnd = [];
        $photoAlbums = $this->_PhotoAlbumRepository->all();
        foreach($photoAlbums as $photoalbum){
            $album = [];
            Photo:$photo = $this->_PhotoRepository->getFirstPhoto($photoalbum->id);
            if($photo){
                $photoalbum->description = str_replace('\n','<br>', $photoalbum->description);
                $album["photoalbum"] = $photoalbum;
                $album["thumbnail"] = $this->_PhotoRepository->retrieveFromAWS($photo->thumbnail);
                array_push($albumsToFrontEnd, $album);
            }
        }

        $curPageName = trans('PhotoAlbums.photoalbums');
        return view('front-end.photo_album_list', compact('curPageName', 'albumsToFrontEnd'));
    }
}
