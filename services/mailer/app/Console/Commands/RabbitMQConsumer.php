<?php

namespace App\Console\Commands;

use App\Interfaces\Services\RabbitMQServiceInterface;
use App\Jobs\Email\SendDailyEmailJob;
use Illuminate\Console\Command;

class RabbitMQConsumer extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:rabbitmq-consumer';

    /**
     * @var string
     */
    protected $description = 'RabbitMQ consumer';

    /**
     * @param RabbitMQServiceInterface $rabbitMQService
     * @return void
     */
    public function handle(RabbitMQServiceInterface $rabbitMQService): void
    {
        $rabbitMQService->consumeMessages('return-subscribers', function ($message) {
            /** @var object{
             *     emails: array<string>,
             *     buy_rate: float,
             *     sale_rate: float,
             * } $data
             */
            $data = json_decode($message->getBody());

            foreach ($data->emails as $email) {
                SendDailyEmailJob::dispatch(
                    $email,
                    $data->buy_rate,
                    $data->sale_rate
                );

                $this->info('Email sent to: ' . $email);
            }
        });
    }
}
