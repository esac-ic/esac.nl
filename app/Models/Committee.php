<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Committee extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'abbreviation',
        'description',
        'email',
    ];
    
    public function chair(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    
    public function getMemberCount(): int
    {
        return $this->members()->count();
    }
}
