<?php

use Illuminate\Support\Facades\Schedule;

// remove:oldUsers and remove:oldApplications run by hand until the board decides on the GDPR cleanup
Schedule::command('auth:clear-resets')->weekly();
Schedule::command('queue:prune-failed --hours=168')->daily();
