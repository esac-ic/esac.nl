<?php

namespace Database\Seeders;

use App\Models\Reimbursement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReimbursementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reimbursement = new Reimbursement();
        $reimbursement->title = 'Intro Borrel';
        $reimbursement->text = 'Reimbursement for the Intro Borrel organized by Intro';
        $reimbursement->status = "In Progress";
        $reimbursement->type = "Activity";
        $reimbursement->createdOn = Carbon::now();
        $reimbursement->createdBy = 1;
        $reimbursement->save();
    }
}
