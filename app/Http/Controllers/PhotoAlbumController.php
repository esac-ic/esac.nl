<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PhotoAlbum;
use App\repositories\PhotoAlbumRepository;
use App\repositories\RepositorieFactory;
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
        $this->_PhotoRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$PHOTOREPOKEY);
    }

    public function index(){
        $photoAlbums = $this->_PhotoAlbumRepository->all(array('title','date'));
        return view('beheer.photoAlbum.index', compact('photoAlbums'));
    }

    public function show(Request $request, PhotoAlbum $photoAlbum){
        return view('beheer.photoAlbum.show', compact('photoAlbum'));
    }

    public function store(Request $request){
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

    public function edit(Request $request, PhotoAlbum $photoAlbum){
        $fields = ['title' => trans('photoAlbum.edit'),
            'method' => 'PATCH',
            'url' => '/photoAlbums/'. $photoAlbum->id];

        return view('beheer.photoAlbum.create_edit', compact('fields','photoAlbum'));
    }

    public function update(Request $request, PhotoAlbum $photoAlbum){
        $this->validateInput($request);

        $this->_PhotoAlbumRepository->update($photoAlbum->id,$request->all());

        Session::flash("message",trans('photoAlbum.edited'));
        return redirect('/photoAlbums');
    }

    public function destroy(Request $request, PhotoAlbum $photoAlbum){
        $this->_PhotoAlbumRepository->delete($photoAlbum->id);

        Session::flash("message",trans('photoAlbum.deleted'));
        return redirect('/photoAlbums');
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'date' => 'required|date',
        ]);
    }

    public function addPhotoToAlbum(Request $request, $ablumId){
        PhotoAlbum:$photoAlbum = $this->_PhotoAlbumRepository->find($ablumId);
        $photos = $request->photos;
        $thumbnails = $request->thumbnails;
        if($photos !=null && $thumbnails  !=null){
            for ($i = 0; $i < count($photos); $i++) {
                $image = $photos[$i];
                $thumbnail = $thumbnails[$i];
                $fileExtension = $photos[$i]->clientExtension();
                if($fileExtension == "png"||"jpeg"||"jpg"){
                    Photo:$photo = $this->_PhotoRepository->create(["album" => $photoAlbum]);
                    $imageFileName = $photo->id . '.' . $fileExtension;
                    $thumbnailFileName = $photo->id .'_thumbnail' . '.' . $fileExtension;
                    $albumtitle = str_replace(' ', '_',$photo->photo_album->title);
                    $thumbnailPath = 'photos/' . $albumtitle .'/' . $thumbnailFileName ;
                    $photoPath = 'photos/' . $albumtitle .'/' . $imageFileName;
            
                    $photoLink = $this->_PhotoRepository->saveToCloud($photoPath, $image);
                    $thumbnailLink = $this->_PhotoRepository->saveToCloud($thumbnailPath, $thumbnail);
                    if($photoLink != null && $thumbnailLink != null){
                        $photoLink = $this->_PhotoRepository->getFileLink($photoLink);
                        $thumbnailLink = $this->_PhotoRepository->getFileLink($thumbnailLink);
                        $Photodemensions = getimagesize($photoLink);
                        $this->_PhotoRepository->update($photo->id,["link" => $photoLink, "thumbnail" => $thumbnailLink, "width" => $Photodemensions[0], "height" => $Photodemensions[1] ]);
                        
                        //Photoalbum thumbnail link
                        if($photoAlbum->thumbnail == null){
                            $this->_PhotoAlbumRepository->updateThumbnail($photoAlbum->id, ["thumbnail" => $thumbnailLink]);
                        }
                        return $photo->id;
                    }                
                }
            }
        }
        return redirect()->route('PhotoAlbum', ['albumId' => $ablumId]);
    }
}
