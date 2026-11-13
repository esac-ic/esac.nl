<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReimbursementItem extends Model
{
    protected $fillable = [
        'item',
        'amount',
        'esacpas',
        'paidBy',
        'image_url'
    ];

    protected $casts = [
        'esacpas' => 'boolean',
    ];

    public function getReimbursement(): HasOne
    {
        return $this->hasOne(Reimbursement::class, 'id', "reimbursement_id");
    }
}
