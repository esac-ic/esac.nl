<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\repositories\PhotoAlbumRepository;
use App\repositories\RepositorieFactory;

class PhotoController extends Controller
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
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->all();
        //return photoalbum overview.
    }

    public function getPhotoAlbum($id){
        //return photoalbum view with photo thumbnails
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($id);
        if($photoAlbum != null){
            $curPageName = trans('PhotoAlbums.photoalbums');
            return view('front-end.photo_upload', compact('curPageName', 'photoAlbum'));
        }else{
            abort(404);
        }
    } 
    public function getPhoto(){
        //return 1080p photo.
    }

    public function addAlbum(Request $request, $title){
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->create(["album" => $title]);
        $this->addPhotoToAlbum($request, $photoAlbum->id);
    }  

    public function addPhotoToAlbum(Request $request, $ablumId){
        //get title from given album
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($ablumId);

        if($files=$request->file('images')){
            foreach($files as $file){
                //check if file extension are images
                $name=$file->getClientOriginalExtension();
                if($name == "png"||"jpeg"||"jpg"||"gif"||"svg"){
                    Photo:$photo = $this->_PhotoRepository->create(["album" => $photoAlbum]);
                    $imageFileName = $photo->id . '.' . $file->getClientOriginalExtension();
                    $albumtitle = str_replace(' ', '_',$photo->photo_album->title);
                    $filePath = '/photos/' . $albumtitle .'/' . $imageFileName;
                    $link = "";
                    if($link = $this->_PhotoRepository->saveToAWS($filePath,$file)){
                        //TODO get AWS Link after saving.           
                        $this->_PhotoRepository->update($photo->id,["link" => $link] );
                    }
                }
            }
        }
    }
}
