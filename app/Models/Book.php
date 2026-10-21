<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'title',
    'year',
    'country',
    'type',
    'code',
])]
class Book extends Model
{
    use SoftDeletes;
}
