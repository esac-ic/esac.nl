<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('auth:clear-resets')->weekly();
