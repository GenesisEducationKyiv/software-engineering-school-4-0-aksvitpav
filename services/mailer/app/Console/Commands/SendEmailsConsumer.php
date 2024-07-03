<?php

namespace App\Console\Commands;

use App\Interfaces\Services\RabbitMQServiceInterface;
use App\Jobs\Email\SendDailyEmailJob;
use Illuminate\Console\Command;

class SendEmailsConsumer extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:send-emails';

    /**
     * @var string
     */
    protected $description = 'Send emails consumer';

    /**
     * @param RabbitMQServiceInterface $rabbitMQService
     * @return void
     */
    public function handle(RabbitMQServiceInterface $rabbitMQService): void
    {
        $rabbitMQService->consumeMessages('email', function ($message) {
            /** @var object{
             *     email: string,
             *     buy_rate: float,
             *     sale_rate: float,
             * } $data
             */
            $data = json_decode($message->getBody());

            SendDailyEmailJob::dispatch(
                $data->email,
                $data->buy_rate,
                $data->sale_rate
            );

            $this->info('Email sent to: ' . $data->email);
        });
    }
}
