<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailListMember extends Model
{
    public $address;
    public $name;
    public $subscribed;
}
