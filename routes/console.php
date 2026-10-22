<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('members:anonymize-former')->weekly();
Schedule::command('registrations:prune')->weekly();
Schedule::command('auth:clear-resets')->weekly();
Schedule::command('queue:prune-failed --hours=168')->daily();
