<?php

namespace App\Console\Commands;

use App\Commands\GetSubscribersCommand;
use Illuminate\Console\Command;

class SendDailyEmailsCommand extends Command
{
    protected $signature = 'app:send-daily-emails';

    protected $description = 'Send daily emails for subscribers';

    /**
     * @param GetSubscribersCommand $getSubscribersCommand
     * @return void
     */
    public function handle(GetSubscribersCommand $getSubscribersCommand): void
    {
        $getSubscribersCommand->execute();
    }
}
