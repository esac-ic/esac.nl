<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Committee extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $with = ['members'];
    
    protected $fillable = [
        'name',
        'abbreviation',
        'email',
        'description',
        'is_chair',
    ];
    
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'committee_member', 'committee_id', 'member_id')
            ->withPivot('is_chair')
            ->withTimestamps();
    }
    
    public function chair(): ?User
    {
        $chairCol = $this->members()->wherePivot('is_chair', true);
        if ($chairCol->count() > 1) {
            \Log::warning("Committee " . $this->name . " has multiple members marked as chair.");
        }
        return $chairCol->first();
    }
}
