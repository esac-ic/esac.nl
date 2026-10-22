<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'text',
    'image_url',
    'thumbnail_url',
    'author',
])]
class NewsItem extends Model
{
    use HasFactory, SoftDeletes;

    public function getImageUrl(): string
    {
        return $this->image_url != ''
        ? Storage::disk('public')->url($this->image_url)
        : '/img/header-3.jpg';
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnail_url != ''
        ? Storage::disk('public')->url($this->thumbnail_url)
        : '/img/header-3.jpg';
    }
}
