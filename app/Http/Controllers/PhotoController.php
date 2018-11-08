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

    public function index($id)
    {
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($id);
        $thumbnails = $this->getThumbnails($id);
        $photos = $this->getPhotos($id); 
        $curPageName = trans('PhotoAlbums.photoalbums');
        return view('front-end.photo_album', compact('curPageName', 'photoAlbum', 'thumbnails', 'photos'));
    }

    //List of photos.
    public function getPhotos($id){
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($id);
        if($photoAlbum != null){
            $photos=[];
            $photosObjects = $this->_PhotoAlbumRepository->getThumbnails($photoAlbum->id);
            foreach($photosObjects as $value){
                $link = $this->_PhotoRepository->retrieveFromAWS($value->link);
                $demensions = getimagesize($link);
                $photo = array($link, $demensions[0], $demensions[1]);
                array_push($photos, $photo);
            }
            return $photos;
        } else{
            abort(404);
        }
    }

    public function getThumbnails($id){
        //Check if given photoalbum exists
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($id);
        if($photoAlbum != null){
            $photos=[];
            $photosObjects = $this->_PhotoAlbumRepository->getThumbnails($photoAlbum->id);
            foreach($photosObjects as $value){
                $photos[$value->id] = $this->_PhotoRepository->retrieveFromAWS($value->thumbnail);;
            }
            return $photos;
        } else{
            abort(404);
        }
    }

    public function addAlbum(Request $request){
        $title = $request->title;
        $description = $request->description;
        if($title!= null && $description !=null){
            PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->create(["title" => $title, "description"=> $description]);
            $this->addPhotoToAlbum($request, $photoAlbum->id);
            return $photoAlbum->id;
        } 
    }  

    public function addPhotoToAlbum(Request $request, $ablumId){
        //get title from given album
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($ablumId);
        $photos = $request->photos;
        $thumbnails = $request->thumbnails;
        if($photos !=null && $thumbnails  !=null){
            for ($i = 0; $i < count($photos); $i++) {
                $photo = $photos[$i];
                $thumbnail = $thumbnails[$i];
                $fileExtension = $photos[$i]->getClientOriginalExtension();
                if($fileExtension == "png"||"jpeg"||"jpg"||"gif"||"svg"){  
                    $this->savePhoto($photo, $photoAlbum, $thumbnail);
                }
            }                        
        }
        return redirect()->route('PhotoAlbum', ['albumId' => $ablumId]);
    }

    public function savePhoto($image, $photoAlbum, $thumbnail){
        Photo:$photo = $this->_PhotoRepository->create(["album" => $photoAlbum]);
        $imageFileName = $photo->id . '.png';
        $thumbnailFileName = $photo->id .'_thumbnail' . '.png';
        $albumtitle = str_replace(' ', '_',$photo->photo_album->title);
        $thumbnailPath = 'photos/' . $albumtitle .'/' . $thumbnailFileName ;
        $photoPath = 'photos/' . $albumtitle .'/' . $imageFileName; 

        $photoLink = $this->_PhotoRepository->saveToAWS($photoPath,$image);
        $thumbnailLink = $this->_PhotoRepository->saveToAWS($thumbnailPath,$thumbnail);
        if($photoLink != null && $thumbnailLink != null){
            $this->_PhotoRepository->update($photo->id,["link" => $photoLink, "thumbnail" => $thumbnailLink]);
            return $photo->id;
        }
    }
}
