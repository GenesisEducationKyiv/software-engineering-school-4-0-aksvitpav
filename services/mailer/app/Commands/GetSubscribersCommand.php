<?php

namespace App\Commands;

use App\Interfaces\Services\RabbitMQServiceInterface;

class GetSubscribersCommand
{
    public const string COMMAND_NAME = 'get-subscribers';

    public function __construct(private readonly RabbitMQServiceInterface $rabbitMQService)
    {
    }

    public function execute(): void
    {
        /** @var string $message */
        $message = json_encode([
            'date' => now()->startOfDay()->toDateTimeString(),
        ]);

        $this->rabbitMQService->sendMessage(
            self::COMMAND_NAME,
            $message
        );
    }
}
