<?php

namespace App\Console\Commands;

use App\Actions\SendDailyEmailsAction;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

class SendDailyEmailsCommand extends Command
{
    protected $signature = 'app:send-daily-emails';

    protected $description = 'Send daily emails for subscribers';

    /**
     * @param SendDailyEmailsAction $sendDailyEmailsAction
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(SendDailyEmailsAction $sendDailyEmailsAction): void
    {
        $sendDailyEmailsAction->execute();
    }
}
