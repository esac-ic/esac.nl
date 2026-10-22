<?php

namespace App\Console\Commands;

use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Description('Anonymise the registrations of events that ended more than the save period ago, so they still count but no longer point to anyone')]
#[Signature('remove:oldApplications')]
class RemoveOldApplications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $old = ApplicationResponse::withTrashed()
            ->whereRelation('agendaItem', 'end_date', '<', Carbon::now()->subYears(config('custom.application_response_save_period')));

        DB::transaction(function () use ($old) {
            ApplicationResponseRow::withTrashed()->whereIn('application_response_id', $old->clone()->select('id'))->forceDelete();
            // A cancelled registration never was a participation
            $old->clone()->onlyTrashed()->forceDelete();
            $old->clone()->whereNotNull('user_id')->update(['user_id' => null]);
        });
    }
}
