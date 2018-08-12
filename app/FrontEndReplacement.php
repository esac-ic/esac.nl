<?php
/**
 * Created by PhpStorm.
 * User: otherwise777
 * Date: 5/25/2017
 * Time: 22:20
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontEndReplacement extends Model {
    use SoftDeletes;
    protected $fillable = [
        'word',
        'replacement'
    ];
}