<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Removes the data of old users except their name from the database if they are old user for more than 2 years')]
#[Signature('remove:oldUsers')]
class RemoveOldUsers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::query()
            ->whereDate('lid_af', '<', now()->subYears(config('custom.old_users_save_period')))
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
                'incasso' => false,
                'remark' => null,
                'remember_token' => null,
            ]);
    }
}
