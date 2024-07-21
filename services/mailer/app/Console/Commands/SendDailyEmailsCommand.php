<?php

namespace App\Console\Commands;

use App\Jobs\MessageBroker\GetSubscribersJob;
use Illuminate\Console\Command;

class SendDailyEmailsCommand extends Command
{
    protected $signature = 'app:send-daily-emails';

    protected $description = 'Send daily emails for subscribers';

    public function handle(): void
    {
        GetSubscribersJob::dispatch()->onQueue('subscriber');
    }
}
