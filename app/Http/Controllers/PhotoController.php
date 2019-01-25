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
        $photoAlbum->description = str_replace("\r\n","<br>", $photoAlbum->description);
        $photos = $this->getPhotos($id); 
        $curPageName = $photoAlbum->title;
        return view('front-end.photo_album', compact('curPageName', 'photoAlbum', 'photos'));
    }

    //List of photos.
    public function getPhotos($id){
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($id);
        if($photoAlbum != null){
            $photosObjects = $this->_PhotoAlbumRepository->getThumbnails($photoAlbum->id);
            return $photosObjects;
        } else{
            abort(404);
        }
    }

    public function addAlbum(Request $request){
        $title = $request->title;
        $description = $request->description;
        $date = $request->captureDate;
        if($title!= null && $description !=null && $date != null){
            if(strlen($title) < 256 && strlen($description) < 256 ){
                PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->create(["title" => $title, "description"=> $description, "date" => $date]);
                $this->addPhotoToAlbum($request, $photoAlbum->id);
                return $photoAlbum->id;
            } else{
                redirect()->back()->withErrors(["error" => trans('front-end/photo.inputToLong')]);
            }
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
                $fileExtension = $photos[$i]->clientExtension();
                if($fileExtension == "png"||"jpeg"||"jpg"||"gif"||"svg"){  
                    $this->savePhoto($photo, $photoAlbum, $thumbnail, $fileExtension);
                }
            }                        
        }
        return redirect()->route('PhotoAlbum', ['albumId' => $ablumId]);
    }

    public function savePhoto($image, $photoAlbum, $thumbnail, $fileExtension){
        Photo:$photo = $this->_PhotoRepository->create(["album" => $photoAlbum]);
        $imageFileName = $photo->id . '.' . $fileExtension;
        $thumbnailFileName = $photo->id .'_thumbnail' . '.' . $fileExtension;
        $albumtitle = str_replace(' ', '_',$photo->photo_album->title);
        $thumbnailPath = 'photos/' . $albumtitle .'/' . $thumbnailFileName ;
        $photoPath = 'photos/' . $albumtitle .'/' . $imageFileName; 

        $photoLink = $this->_PhotoRepository->saveToAWS($photoPath,$image);
        $thumbnailLink = $this->_PhotoRepository->saveToAWS($thumbnailPath,$thumbnail);
        if($photoLink != null && $thumbnailLink != null){
            $photoLink = $this->_PhotoRepository->getAWSLink($photoLink);
            $thumbnailLink = $this->_PhotoRepository->getAWSLink($thumbnailLink);
            $Photodemensions = getimagesize($photoLink);
            $this->_PhotoRepository->update($photo->id,["link" => $photoLink, "thumbnail" => $thumbnailLink, "width" => $Photodemensions[0], "height" => $Photodemensions[1] ]);
            
            //Photoalbum thumbnail link
            if($photoAlbum->thumbnail == null){
                $this->_PhotoAlbumRepository->update($photoAlbum->id, ["thumbnail" => $thumbnailLink]);
            }
            return $photo->id;
        }
    }
}
