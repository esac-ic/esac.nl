<?php

namespace App\Models\Reimbursement;

use Illuminate\Database\Eloquent\Model;

class ReimbursementReceipt extends Model
{
    protected $fillable = [
        'image_url'
    ];

    public function getReimbursement()
    {
        return $this->belongsTo(Reimbursement::class, 'reimbursement_id', 'id');
    }

    public function getImageUrl()
    {
        if ($this->image_url != "") {
            return Storage::disk('public')->url($this->image_url);
        } else {
            return "/img/missing_receipt.png";
        }
    }
}
