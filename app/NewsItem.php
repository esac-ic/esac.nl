<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsItem extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'text',
        'image_url',
        'thumbnail_url',
        'author'
    ];

    public function newsItemText(){
        return $this->hasOne('App\Text', 'id', 'text');
    }

    public function newsItemTitle()
    {
        return $this->hasOne('App\Text', 'id', 'title');
    }

    public function getImageUrl(){
        if($this->image_url != ""){
            return \Storage::disk('public')->url($this->image_url);
        } else {
            return "/img/header-3.jpg";
        }
    }

    public function getThumbnailUrl(){
        if($this->thumbnail_url != ""){
            return \Storage::disk('public')->url($this->thumbnail_url);
        } else {
            return "/img/header-3.jpg";
        }

    }
}
