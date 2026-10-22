<?php

namespace App\Console\Commands;

use App\Models\User;
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
            ->whereDate('lid_af', '<', Carbon::now()->subYears(config('app.old_user_retention_years')))
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
            ]);
    }
}
