<?php
/**
 * Created by Vim.
 * User: Tim Commandeur
 * Date: 15/05/2019
 * Time: 15:36
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialRent extends Model {
    use SoftDeletes;

    protected $primaryKey = 'rent_id';

    protected $fillable = [
	    'itemType',
	    'rentStart',
	    'rentEnd'
        
    ];



}
