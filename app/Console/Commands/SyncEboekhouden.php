<?php

namespace App\Console\Commands;

use App\CustomClasses\Eboekhouden\EboekhoudenFacade;
use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncEboekhouden extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eboekhouden:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make sure all the data in eboekhouden is the same as on the website';

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
    public function handle(EboekhoudenFacade $eboekhouden)
    {
        \Log::info("Performing an eboekhouden list sync");
        
        User::all()->each(function ($user) use($eboekhouden) {
            $eboekhouden->syncRelation($user);
        });
        
        \Log::info("Eboekhouden sync finished");
    }
}
