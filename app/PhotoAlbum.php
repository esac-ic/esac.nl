<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhotoAlbum extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'date',
        'thumbnail',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
