<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveOldUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:oldUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the data of old users except their name from the database if they are old user for more than 2 years';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::query()
            ->whereDate('lid_af', '<', Carbon::now()->subYears(config('custom.old_users_save_period')))
            ->update([
                'email' => null,
                'password' => null,
                'street' => null,
                'houseNumber' => null,
                'city' => null,
                'zipcode' => null,
                'country' => null,
                'phonenumber' => null,
                'phonenumber_alt' => null,
                'emergencyNumber' => null,
                'emergencyHouseNumber' => null,
                'emergencystreet' => null,
                'emergencycity' => null,
                'emergencyzipcode' => null,
                'emergencycountry' => null,
                'birthDay' => null,
                'kind_of_member' => null,
                'IBAN' => null,
                'BIC' => null,
                'incasso' => false,
                'remark' => null,
            ]);
    }
}
