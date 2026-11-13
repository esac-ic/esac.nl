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
        # Standard
        $reimbursement = new Reimbursement();
        $reimbursement->title = 'Intro Borrel';
        $reimbursement->text = 'Reimbursement for the Intro Borrel organized by Intro';
        $reimbursement->status = "Draft";
        $reimbursement->type = "Activity";
        $reimbursement->createdOn = Carbon::now();
        $reimbursement->createdBy = 1;
        $reimbursement->save();

        # Kilometer
        $reimbursement = new Reimbursement();
        $reimbursement->title = 'Intro Borrel';
        $reimbursement->text = 'Reimbursement for the Intro Borrel organized by Intro';
        $reimbursement->status = "Pending";
        $reimbursement->type = "Driver";
        $reimbursement->createdOn = Carbon::now()->subDays(3);
        $reimbursement->createdBy = 1;
        $reimbursement->save();

        # Different user
        $reimbursement = new Reimbursement();
        $reimbursement->title = 'Intro Borrel';
        $reimbursement->text = 'Reimbursement for the Intro Borrel organized by Intro';
        $reimbursement->status = "Rejected";
        $reimbursement->type = "Activity";
        $reimbursement->createdOn = Carbon::now();
        $reimbursement->createdBy = 2;
        $reimbursement->save();
    }
}
