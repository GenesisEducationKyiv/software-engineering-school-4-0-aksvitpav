<?php

namespace App\Console\Commands;

use App\Actions\SendDailyEmailsAction;
use App\Exceptions\SendingEmailException;
use Illuminate\Console\Command;

class SendDailyEmailsCommand extends Command
{
    protected $signature = 'app:send-daily-emails';

    protected $description = 'Send daily emails for subscribers';

    /**
     * @param SendDailyEmailsAction $sendDailyEmailsAction
     * @return void
     * @throws SendingEmailException
     */
    public function handle(SendDailyEmailsAction $sendDailyEmailsAction): void
    {
        $sendDailyEmailsAction->execute();
    }
}
