<?php

use App\Console\Commands\SendDailyEmailsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SendDailyEmailsCommand::class)->hourly();
