<?php

namespace App\Actions;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Interfaces\Services\RabbitMQServiceInterface;

class SendDailyEmailsAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     * @param SendEmailAction $sendEmailAction
     * @param RabbitMQServiceInterface $rabbitMQService
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
        protected SendEmailAction $sendEmailAction,
        protected RabbitMQServiceInterface $rabbitMQService,
    ) {
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $startToday = now()->startOfDay();

        $channelName = uniqid('return_current_rate_');
        $this->rabbitMQService->sendMessage('get_current_rate', 'Hi!');
//        if (!$currencyRate) {
//            Log::error('Current rate not found. Can\'t send mails.');
//            return;
//        }
//
//        $subscribers = $this->subscriberRepository->getNotEmailedSubscribers($startToday);
//
//        foreach ($subscribers as $subscriber) {
//            $this->sendEmailAction->execute($subscriber, SendDailyEmailJob::class, $currencyRate);
//        }
    }
}
