<?php

namespace App\Actions;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Jobs\SendDailyEmailJob;
use App\Models\CurrencyRate;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;

class SendDailyEmailsAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param GetCurrentRateAction $getCurrentRateAction
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected GetCurrentRateAction $getCurrentRateAction,
    ) {
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $startToday = now()->startOfDay();

        /** @var CurrencyRate|null $currencyRate */
        $currencyRate = $this->getCurrentRateAction->execute();

        if (! $currencyRate) {
            Log::error('Current rate not found. Can\'t send mails.');
            return;
        }

        $subscribers = $this->subscriberRepository->getNotEmailedSubscribers($startToday);

        foreach ($subscribers as $subscriber) {
            /** @var Subscriber $subscriber */
            SendDailyEmailJob::dispatch($subscriber, $currencyRate);
        }
    }
}
