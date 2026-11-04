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
        $reimbursementItem->save();
    }
}
