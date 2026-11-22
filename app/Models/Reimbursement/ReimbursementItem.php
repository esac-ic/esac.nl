<?php

namespace App\Models\Reimbursement;

use Illuminate\Database\Eloquent\Model;

class ReimbursementItem extends Model
{
    protected $fillable = [
        'item',
        'amount',
        'esacpas',
        'paidBy',
    ];

    protected $casts = [
        'esacpas' => 'boolean',
    ];

    public function getReimbursement(): BelongsTo
    {
        return $this->belongsTo(Reimbursement::class, 'reimbursement_id', 'id');
    }
}
