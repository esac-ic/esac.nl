<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    public $incrementing = true;
    protected $fillable = [
        'link',
    ];

    public function photo_album(){
        return $this->belongsTo(PhotoAlbum::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'createdBy');
    }

}
