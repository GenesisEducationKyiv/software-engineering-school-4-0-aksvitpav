<?php

namespace App\Actions;

use App\Exceptions\SendingEmailException;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Jobs\Email\SendDailyEmailJob;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;

class SendDailyEmailsAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param SendEmailAction $sendEmailAction
     * @param CurrencyRateAdapterInterface $currencyRateAdapter
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected SendEmailAction $sendEmailAction,
        protected CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {
    }

    /**
     * @return void
     * @throws SendingEmailException
     * @throws BindingResolutionException
     */
    public function execute(): void
    {
        $startToday = now()->startOfDay();

        $currencyRate = $this->currencyRateAdapter->getCurrencyRate();

        if (!$currencyRate) {
            Log::error('Current rate not found. Can\'t send mails.');
            return;
        }

        $subscribers = $this->subscriberRepository->getNotEmailedSubscribers($startToday);

        foreach ($subscribers as $subscriber) {
            $this->sendEmailAction->execute($subscriber, SendDailyEmailJob::class, $currencyRate);
        }
    }
}
