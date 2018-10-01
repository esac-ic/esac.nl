<?php
/**
 * Created by PhpStorm.
 * User: IA02UI
 * Date: 15-6-2017
 * Time: 20:39
 */

namespace App\repositories;


use App\PhotoAlbum;
use App\Photo;
use Illuminate\Support\Facades\Auth;

class PhotoAlbumRepository implements IRepository
{

    public function create(array $data)
    {
        $album = new PhotoAlbum();
        $album->title = $data["title"];
        $album->user()->associate(Auth::user()->id);
        $album->save();
        return $album;
    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        PhotoAlbum::destroy($id);
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return PhotoAlbum::where($field, '=',$value)->orderBy('id', 'desc')->get($columns);
    }

    public function all($columns = array('*'))
    {
        return PhotoAlbum::query()->orderBy('id', 'desc')->get();
    }

    public function getThumbnails($id){
        return Photo::with('photo_album')->where('photo_album_id',$id)->get();    
    }
}