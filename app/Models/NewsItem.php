<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'body',
    'image_path',
    'thumbnail_path',
    'author',
])]
class NewsItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The URL of the header image, or of the default header when there is none.
     *
     * @return Attribute<string, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->image_path != ''
            ? Storage::disk('public')->url($this->image_path)
            : '/img/header-3.jpg');
    }

    /**
     * The URL of the thumbnail, or of the default header when there is none.
     *
     * @return Attribute<string, never>
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->thumbnail_path != ''
            ? Storage::disk('public')->url($this->thumbnail_path)
            : '/img/header-3.jpg');
    }
}
