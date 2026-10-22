<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Erase all but the name of members who left longer ago than the retention period')]
#[Signature('remove:oldUsers')]
class RemoveOldUsers extends Command
{
    public function handle(): void
    {
        User::query()
            ->whereDate('membership_ended_on', '<', now()->subYears(config('custom.old_users_save_period')))
            ->update([
                'email' => null,
                'password' => null,
                'street' => null,
                'house_number' => null,
                'city' => null,
                'zipcode' => null,
                'country' => null,
                'phone_number' => null,
                'phone_number_alt' => null,
                'emergency_number' => null,
                'emergency_house_number' => null,
                'emergency_street' => null,
                'emergency_city' => null,
                'emergency_zipcode' => null,
                'emergency_country' => null,
                'birthday' => null,
                'kind_of_member' => null,
                'iban' => null,
                'bic' => null,
                'direct_debit' => false,
                'remark' => null,
                'remember_token' => null,
            ]);
    }
}
