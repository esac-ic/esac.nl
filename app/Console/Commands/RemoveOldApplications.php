<?php

namespace App\Console\Commands;

use App\Models\ApplicationForm\ApplicationResponse;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Remove old application that are older then x years')]
#[Signature('remove:oldApplications')]
class RemoveOldApplications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ApplicationResponse::query()
            ->where('created_at', '<', Carbon::now()->subYears(config('custom.application_response_save_period')))
            ->forceDelete();
    }
}
