<?php
/**
 * Created by PhpStorm.
 * User: IA02UI
 * Date: 15-6-2017
 * Time: 20:39
 */

namespace App\repositories;


use App\Photo;
use Illuminate\Support\Facades\Auth;
use obregonco\B2\Client;
use obregonco\B2\Bucket;

class PhotoRepository implements IRepository
{

    public function create(array $data)
    {
        $photo = new Photo();
        $photo->photo_album()->associate($data["album"]);
        $photo->user()->associate(Auth::user()->id);
        $photo->save();
        return $photo;
    }

    public function update($id, array $data)
    {
        $photo = $this->find($id);
        $photo->update($data);
        $photo->save();
        return $photo;  
    }

    public function delete($id)
    {
        Photo::destroy($id);
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Photo::where($field, '=',$value)->orderBy('id', 'desc')->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Photo::query()->orderBy('id', 'desc')->get();
    }

    public function getFirstPhoto($id){
        $m =Photo::with('photo_album')->where('photo_album_id',$id)->first();
        return $m;
    }

    public function getFileLink($filepath){
        return env("B2_STORAGE_URL") . "/" . $filepath;
    }

    public function saveToCloud($filepath, $file){
        $client = new Client(env('B2_ACCOUNT_KEY_ID'), [
            'applicationKey' => env('B2_APPLICATION_KEY'),
            'version' => '2',
        ]);
        
        $file = $client->upload([
            'BucketName' => env('B2_BUCKETNAME'),
            'FileName' => $filepath,
            'Body' => file_get_contents($file)
        ]);

        return $filepath;
    }
}