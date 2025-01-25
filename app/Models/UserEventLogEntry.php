<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\UserEventTypes;
use App\User;

class UserEventLogEntry extends Model
{
    // use HasFactory;
    
    protected $fillable = [
        'eventType',
        'eventDetails',
    ];
    
    /**
     * Related user object
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
    
    /**
     * Checks if the entry has a valid event type
     * 
     * @return bool
     */
    public function isValidEventType(): bool
    {
        return in_array($this->eventType, UserEventTypes::values());
    }
}
