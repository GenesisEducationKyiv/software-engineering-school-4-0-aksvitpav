<?php

namespace App\Actions;

use App\Commands\ReturnSubscribersCommand;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\RabbitMQServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;

class GetNotEmailedSuscribersAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param CurrencyRateAdapterInterface $currencyRateAdapter
     * @param RabbitMQServiceInterface $rabbitMQService
     * @param ReturnSubscribersCommand $returnSubscribersCommand
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected CurrencyRateAdapterInterface $currencyRateAdapter,
        protected RabbitMQServiceInterface $rabbitMQService,
        protected ReturnSubscribersCommand $returnSubscribersCommand,
    ) {
    }

    /**
     * @param Carbon $date
     * @return void
     * @throws BindingResolutionException
     */
    public function execute(Carbon $date): void
    {
        $currencyRate = $this->currencyRateAdapter->getCurrencyRate();

        $subscribers = $this->subscriberRepository->getNotEmailedSubscribers($date);

        $this->returnSubscribersCommand->execute($currencyRate, $subscribers);
    }
}
