<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Delete the event registrations older than the retention period')]
#[Signature('registrations:prune')]
class PruneRegistrations extends Command
{
    public function handle(): void
    {
        Registration::query()
            ->where('created_at', '<', now()->subYears(config('app.registration_retention_years')))
            ->forceDelete();
    }
}
