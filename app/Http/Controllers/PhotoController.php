<?php

namespace App\Http\Controllers;

use App\Repositories\PhotoAlbumRepository;
use App\Repositories\PhotoRepository;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    private $_photoAlbumRepository;
    private $_photoRepository;

    public function __construct(PhotoAlbumRepository $photoAlbumRepository, PhotoRepository $photoRepository){
        $this->middleware('auth');
        $this->_photoAlbumRepository = $photoAlbumRepository;
        $this->_photoRepository = $photoRepository;
    }

    public function index($id)
    {
        PhotoAlbum:$photoAlbum = $this->_photoAlbumRepository->find($id);
        $photoAlbum->description = str_replace("\r\n","<br>", $photoAlbum->description);
        $photos = $this->getPhotos($id); 
        $curPageName = $photoAlbum->title;
        return view('front-end.photo_album', compact('curPageName', 'photoAlbum', 'photos'));
    }

    //List of photos.
    public function getPhotos($id){
        PhotoAlbum:$photoAlbum = $this->_photoAlbumRepository->find($id);
        if($photoAlbum != null){
            $photosObjects = $this->_photoAlbumRepository->getThumbnails($photoAlbum->id);
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
                PhotoAlbum:$photoAlbum = $this->_photoAlbumRepository->create(["title" => $title, "description"=> $description, "date" => $date]);
                $this->addPhotoToAlbum($request, $photoAlbum->id);
                return $photoAlbum->id;
            } else{
                redirect()->back()->withErrors(["error" => trans('front-end/photo.inputToLong')]);
            }
        } 
    }  

    public function addPhotoToAlbum(Request $request, $ablumId){
        //get title from given album
        PhotoAlbum:$photoAlbum = $this->_photoAlbumRepository->find($ablumId);
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
        Photo:$photo = $this->_photoRepository->create(["album" => $photoAlbum]);
        $imageFileName = $photo->id . '.' . $fileExtension;
        $thumbnailFileName = $photo->id .'_thumbnail' . '.' . $fileExtension;
        $albumtitle = str_replace(' ', '_',$photo->photo_album->title);
        $thumbnailPath = 'photos/' . $albumtitle .'/' . $thumbnailFileName ;
        $photoPath = 'photos/' . $albumtitle .'/' . $imageFileName;

        $photoLink = $this->_photoRepository->saveToCloud($photoPath, $image);
        $thumbnailLink = $this->_photoRepository->saveToCloud($thumbnailPath, $thumbnail);
        if($photoLink != null && $thumbnailLink != null){
            $photoLink = $this->_photoRepository->getFileLink($photoLink);
            $thumbnailLink = $this->_photoRepository->getFileLink($thumbnailLink);
            $Photodemensions = getimagesize($photoLink);
            $this->_photoRepository->update($photo->id,["link" => $photoLink, "thumbnail" => $thumbnailLink, "width" => $Photodemensions[0], "height" => $Photodemensions[1] ]);
            
            //Photoalbum thumbnail link
            if($photoAlbum->thumbnail == null){
                $this->_photoAlbumRepository->updateThumbnail($photoAlbum->id, ["thumbnail" => $thumbnailLink]);
            }
            return $photo->id;
        }
    }
}
