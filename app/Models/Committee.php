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
    
    public function setChair(User $chair): void
    {
        if (!$this->members->containts($chair))
        {
            \Log::error("Tried to set " . $chair->getName() . " as chair, but isn't a member of " . $this->name);
            throw new \RuntimeException("Tried to set " . $chair->getName() . " as chair, but isn't a member of " . $this->name);
        }
        
        $this->members()->updateExistingPivot($this->chair()->id, ['is_chair' => false]); //reset the previous chair
        $this->members()->updateExistingPivot($chair->id, ['is_chair' => true]); //set the new chair
    }
}
