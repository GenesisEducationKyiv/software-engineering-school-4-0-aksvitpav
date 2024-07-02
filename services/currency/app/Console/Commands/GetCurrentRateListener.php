<?php

namespace App\Console\Commands;

use App\Actions\GetCurrentRateAction;
use App\Interfaces\Services\RabbitMQServiceInterface;
use Illuminate\Console\Command;

class GetCurrentRateListener extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:get-current-rate';

    /**
     * @var string
     */
    protected $description = 'Get current rate listener';

    /**
     * @param RabbitMQServiceInterface $rabbitMQService
     * @param GetCurrentRateAction $action
     * @return void
     */
    public function handle(RabbitMQServiceInterface $rabbitMQService, GetCurrentRateAction $action): void
    {
        $rabbitMQService->consumeMessages('get_current_rate', function ($message) {
            $this->info("Received message: {$message->getBody()}");
        });
    }
}
