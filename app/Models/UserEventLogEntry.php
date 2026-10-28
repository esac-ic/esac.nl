<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $event_type
 * @property string $event_details
 */
class UserEventLogEntry extends Model
{
    use HasFactory;
    
    /**
     * @var mixed|string
     */
    protected $fillable = [
        'event_type',
        'event_details',
    ];
    
    /**
     * Related user object
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
