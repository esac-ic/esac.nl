<?php

namespace Database\Seeders;

use App\Models\Reimbursement\ReimbursementReceipt;
use Illuminate\Database\Seeder;

class ReimbursementReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $receipt = new ReimbursementReceipt();
        $receipt->reimbursement_id = 1;
        $receipt->image_url = "";
        $receipt->save();
        
        $receipt = new ReimbursementReceipt();
        $receipt->reimbursement_id = 1;
        $receipt->image_url = "";
        $receipt->save();
        
        $receipt = new ReimbursementReceipt();
        $receipt->reimbursement_id = 1;
        $receipt->image_url = "";
        $receipt->save();
    }
}
