<?php

namespace App\Actions;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\RabbitMQServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class SendDailyEmailsAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param SetEmailedAtForSubscriberAction $setEmailedAtAction
     * @param CurrencyRateAdapterInterface $currencyRateAdapter
     * @param RabbitMQServiceInterface $rabbitMQService
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected SetEmailedAtForSubscriberAction $setEmailedAtAction,
        protected CurrencyRateAdapterInterface $currencyRateAdapter,
        protected RabbitMQServiceInterface $rabbitMQService,
    ) {
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function execute(): void
    {
        $startToday = now()->startOfDay();

        $currencyRate = $this->currencyRateAdapter->getCurrencyRate();

        $subscribers = $this->subscriberRepository->getNotEmailedSubscribers($startToday);

        foreach ($subscribers as $subscriber) {
            /** @var string $message */
            $message = json_encode([
                'email' => $subscriber->email,
                'buy_rate' => $currencyRate->getBuyRate(),
                'sale_rate' => $currencyRate->getSaleRate(),
            ]);

            $this->rabbitMQService->sendMessage(
                'email',
                $message
            );

            $this->setEmailedAtAction->execute($subscriber->id);
        }
    }
}
