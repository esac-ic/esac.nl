<?php

namespace App\Models\ApplicationForm;

use App\Text;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ApplicationForm
 * @package App\Models\ApplicationForm
 */
class ApplicationForm extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @return mixed
     */
    public function applicationFormName(){
        return $this->hasOne(Text::class, 'id', 'name')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function applicationFormRows(){
        return $this->hasMany(ApplicationFormRow::class,'application_form_id')->withTrashed();
    }
}
