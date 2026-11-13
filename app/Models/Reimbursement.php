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

    public function getAmount()
    {
        $items = $this->hasMany(ReimbursementItem::class, 'reimbursement_id')->get();

        $total = 0;
        foreach ($items as $item) {
            $total += $item->amount;
        }
        return $total;
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, 'id', 'createdBy');
    }
}
