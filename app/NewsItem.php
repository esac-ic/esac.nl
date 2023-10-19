<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class NewsItem extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'text',
        'image_url',
        'thumbnail_url',
        'author',
    ];

    public function getImageUrl()
    {
        return $this->image_url != ""
        ? Storage::disk('public')->url($this->image_url)
        : "/img/header-3.jpg";
    }

    public function getThumbnailUrl()
    {
        return $this->thumbnail_url != ""
        ? Storage::disk('public')->url($this->thumbnail_url)
        : "/img/header-3.jpg";
    }
}
