<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Models\RegistrationAnswer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Description('Anonymise the registrations of events that ended more than the retention period ago, so they still count but no longer point to anyone')]
#[Signature('registrations:prune')]
class PruneRegistrations extends Command
{
    public function handle(): void
    {
        $old = Registration::query()
            ->whereRelation('event', 'end_date', '<', now()->subYears(config('custom.application_response_save_period')));

        DB::transaction(function () use ($old) {
            RegistrationAnswer::query()->whereIn('registration_id', $old->clone()->select('id'))->delete();
            $old->clone()->whereNotNull('user_id')->update(['user_id' => null]);
        });
    }
}
