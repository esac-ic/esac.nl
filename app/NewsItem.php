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
        'createdBy',
        'image_url',
    ];

    public function newsItemText(){
        return $this->hasOne('App\Text', 'id', 'text');
    }

    public function newsItemTitle()
    {
        return $this->hasOne('App\Text', 'id', 'title');
    }

    public function getCreatedBy()
    {
        return $this->hasOne('App\User', 'id', 'createdBy');
    }
    public function getImageUrl(){
        if($this->image_url != ""){
            return \Storage::disk('public')->url($this->image_url);
        } else {
            return "/img/header-3.jpg";
        }

    }
}
