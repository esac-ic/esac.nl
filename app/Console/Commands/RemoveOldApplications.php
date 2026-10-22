<?php

namespace App\Console\Commands;

use App\Models\ApplicationForm\ApplicationResponse;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Delete the event registrations older than the retention period')]
#[Signature('remove:oldApplications')]
class RemoveOldApplications extends Command
{
    public function handle(): void
    {
        ApplicationResponse::query()
            ->where('created_at', '<', now()->subYears(config('app.application_response_retention_years')))
            ->forceDelete();
    }
}
