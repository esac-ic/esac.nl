<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailList extends Model
{
    public $emailDomain;

    protected $fillable = [
        'acces_level',
        'address',
        'name',
        'description',
        'members_count',
        'created_at',
    ];

    public $acces_level;
    public $address;
    public $created_at;
    public $description;
    public $members_count;
    public $name;
    public $members = array();

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->EMAILDOMAIN = config('mailgun.domain');
        $this->acces_level = key_exists('acces_level',$attributes) ? $attributes['acces_level'] : "";
        $this->address = (key_exists('address',$attributes) ? $attributes['address'] : "" ). $this->emailDomain;
        $this->name = key_exists('name',$attributes) ? $attributes['name'] : "";
        $this->description = key_exists('description',$attributes) ? $attributes['description'] : "";
        $this->members_count = key_exists('members_count',$attributes) ? $attributes['members_count'] : "";
        $this->created_at = key_exists('created_at',$attributes) ? $attributes['created_at'] : "";

    }

    public function addMember($member){
        array_push($this->members,$member);
    }
}
