<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('remove:oldUsers')->weekly();
Schedule::command('remove:oldApplications')->weekly();
Schedule::command('auth:clear-resets')->weekly();
Schedule::command('queue:prune-failed --hours=168')->daily();
