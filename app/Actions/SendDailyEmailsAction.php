<?php

namespace App\Actions;

use App\Exceptions\SendingEmailException;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Jobs\Email\SendDailyEmailJob;
use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Log;

class SendDailyEmailsAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param GetCurrentRateAction $getCurrentRateAction
     * @param SendEmailAction $sendEmailAction
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected GetCurrentRateAction $getCurrentRateAction,
        protected SendEmailAction $sendEmailAction,
    ) {
    }

    /**
     * @return void
     * @throws SendingEmailException
     */
    public function execute(): void
    {
        $startToday = now()->startOfDay();

        /** @var CurrencyRate|null $currencyRate */
        $currencyRate = $this->getCurrentRateAction->execute();

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
