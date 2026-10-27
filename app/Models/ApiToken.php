<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ApiToken extends Model
{
    protected $fillable = [
        'token',
        'expires_at',
        'id'
    ];
    public $incrementing = false;
    public $timestamps = false;

    public function isExpired(): bool
    {
        return $this->expires_at < Carbon::now();
    }
}
