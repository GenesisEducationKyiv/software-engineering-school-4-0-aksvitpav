<?php

use App\Console\Commands\ClearCurrencyRateCommand;
use App\Console\Commands\FetchCurrencyRateCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ClearCurrencyRateCommand::class)->daily();
Schedule::command(FetchCurrencyRateCommand::class)->everyFifteenMinutes();
