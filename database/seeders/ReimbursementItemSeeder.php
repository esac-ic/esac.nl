<?php

namespace Database\Seeders;

use App\Models\ReimbursementItem;
use Illuminate\Database\Seeder;

class ReimbursementItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reimbursementItem = new ReimbursementItem();
        $reimbursementItem->reimbursement_id = 1;
        $reimbursementItem->item = 'Cookies';
        $reimbursementItem->amount = 2.76;
        $reimbursementItem->esacpas = 0;
        $reimbursementItem->image_url = "";
        $reimbursementItem->paidBy = 1;
        $reimbursementItem->save();
        
        # Esac pas
        $reimbursementItem = new ReimbursementItem();
        $reimbursementItem->reimbursement_id = 1;
        $reimbursementItem->item = 'Milk';
        $reimbursementItem->amount = 1.34;
        $reimbursementItem->esacpas = 1;
        $reimbursementItem->image_url = "";
        $reimbursementItem->paidBy = 1;
        $reimbursementItem->save();
        
        $reimbursementItem = new ReimbursementItem();
        $reimbursementItem->reimbursement_id = 2;
        $reimbursementItem->item = 'Cookies';
        $reimbursementItem->amount = 1.53;
        $reimbursementItem->esacpas = 0;
        $reimbursementItem->image_url = "";
        $reimbursementItem->paidBy = 1;
        $reimbursementItem->save();
        
        $reimbursementItem = new ReimbursementItem();
        $reimbursementItem->reimbursement_id = 3;
        $reimbursementItem->item = 'Cookies';
        $reimbursementItem->amount = 1.78;
        $reimbursementItem->esacpas = 0;
        $reimbursementItem->image_url = "";
        $reimbursementItem->paidBy = 2;
        $reimbursementItem->save();
    }
}
