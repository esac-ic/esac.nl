<?php

namespace App\Console\Commands;

use App\Models\ApplicationForm\ApplicationResponse;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemoveOldApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:oldApplications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old application that are older then x years';

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
        ApplicationResponse::query()
            ->where('created_at', '<', Carbon::now()->subYears(config('custom.application_response_save_period')))
            ->forceDelete();
    }
}
