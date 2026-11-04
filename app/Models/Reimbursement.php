<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    protected $fillable = [
        'title',
        'text',
        'status',
        'type',
        'createdOn'
    ];

    public function getReimbursementItems()
    {
        return $this->hasMany(ReimbursementItem::class, 'reimbursement_id');
    }
}
