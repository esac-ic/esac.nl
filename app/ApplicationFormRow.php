<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationFormRow extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'application_form_id',
        'type',
        'required',
    ];
    private $_inputfiels = null;

    public function applicationFormRowName(){
        return $this->hasOne('App\Text', 'id', 'name')->withTrashed();
    }

    public function getInputBox(){
        if($this->_inputfiels === null){
            $this->_inputfiels = [
                "Text" => "<input name='" .$this->id."' type='text' class='form-control' id='form_". $this->id ." placeholder='Enter text'>",
                "Cijfer" => "<input name='". $this->id. "' type='number' class='form-control' id='form_". $this->id. "' placeholder='Enter a number'>",
                "Textbox" => "<textarea name='". $this->id. "' id='form_". $this->id. "' class='form-control' ></textarea>",
                "Checkbox" => "<input type='hidden' name='". $this->id. "' value='false' /><input name='". $this->id. "' type='checkbox' class='form-control'>"
            ];
        }
        $inputbox = "<label for='". $this->applicationformrowname->text() ."'>". $this->applicationformrowname->text(). "</label>";
        $inputbox .= $this->_inputfiels[$this->type];
        return $inputbox;
    }
}
