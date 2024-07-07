<?php

namespace App\Console\Commands;

use App\Actions\GetNotEmailedSuscribersAction;
use App\Interfaces\Services\RabbitMQServiceInterface;
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
     * @param GetNotEmailedSuscribersAction $getNotEmailedSuscribersAction
     * @return void
     */
    public function handle(RabbitMQServiceInterface $rabbitMQService, GetNotEmailedSuscribersAction $getNotEmailedSuscribersAction): void
    {
        $rabbitMQService->consumeMessages('get-subscribers', function ($message)/* use ($getNotEmailedSuscribersAction)*/ {
            $this->info('111');
//            /** @var object{date: string} $data */
//            $data = json_decode($message->getBody());
//            $getNotEmailedSuscribersAction->execute(Carbon::parse($data->date));
        });
    }
}
