<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoAlbum extends Model
{
    protected $table = 'photo_albums';
    public $incrementing = true;
    protected $fillable = [
        'title',
        'description',
        'date',
        'thumbnail'
    ];

    public function photos(){
        return $this->hasMany(Photo::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'createdBy');
    }
}
