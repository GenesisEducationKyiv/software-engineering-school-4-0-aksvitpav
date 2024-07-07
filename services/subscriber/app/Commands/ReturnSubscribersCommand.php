<?php

namespace App\Commands;

use App\Interfaces\Services\RabbitMQServiceInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use Illuminate\Support\Collection;

class ReturnSubscribersCommand
{
    public const string COMMAND_NAME = 'return-subscribers';

    public function __construct(private readonly RabbitMQServiceInterface $rabbitMQService)
    {
    }

    public function execute(CurrencyRateVOInterface $currencyRate, Collection $subscribers): void
    {
        /** @var string $message */
        $message = json_encode([
            'emails' => $subscribers->pluck('email')->toArray(),
            'buy_rate' => $currencyRate->getBuyRate(),
            'sale_rate' => $currencyRate->getSaleRate(),
        ]);

        $this->rabbitMQService->sendMessage(
            self::COMMAND_NAME,
            $message
        );
    }
}
