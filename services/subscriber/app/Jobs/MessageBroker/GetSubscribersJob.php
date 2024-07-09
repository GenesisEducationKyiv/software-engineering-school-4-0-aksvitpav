<?php

namespace App\Jobs\MessageBroker;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetSubscribersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(
        CurrencyRateAdapterInterface $currencyRateAdapter,
        SubscriberRepositoryInterface $subscriberRepository
    ): void {
        $date = now()->startOfDay();
        $rate = $currencyRateAdapter->getCurrencyRate();
        $notEmailedSubscribers = $subscriberRepository->getNotEmailedSubscribers($date);

        $rate = [
            'buyRate' => $rate->getBuyRate(),
            'saleRate' => $rate->getSaleRate(),
        ];

        /** @var array{array{"id":int, "email":string}} $notEmailedSubscribers */
        $notEmailedSubscribers = $notEmailedSubscribers->map(function (Subscriber $subscriber) {
            return [
                'id' => $subscriber->id,
                'email' => $subscriber->email,
            ];
        })->toArray();

        ReturnSubscribersJob::dispatch($rate, $notEmailedSubscribers)->onQueue('mailer');
    }
}
