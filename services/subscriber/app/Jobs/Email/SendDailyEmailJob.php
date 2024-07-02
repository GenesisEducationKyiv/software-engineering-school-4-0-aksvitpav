<?php

namespace App\Jobs\Email;

use App\Actions\SetEmailedAtForSubscriberAction;
use App\Interfaces\Jobs\EmailJobInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use App\Mail\CurrentRateMail;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDailyEmailJob implements ShouldQueue, EmailJobInterface
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param Subscriber $subscriber
     * @param CurrencyRateVOInterface $currencyRate
     */
    public function __construct(
        public Subscriber $subscriber,
        public CurrencyRateVOInterface $currencyRate,
    ) {
    }

    /**
     * @param SetEmailedAtForSubscriberAction $setEmailedAtForSubscriberAction
     * @return void
     */
    public function handle(SetEmailedAtForSubscriberAction $setEmailedAtForSubscriberAction): void
    {
        Mail::to($this->subscriber->email)->send(
            new CurrentRateMail(
                USDBuyRate: $this->currencyRate->getBuyRate(),
                USDSaleRate: $this->currencyRate->getSaleRate(),
            )
        );

        $setEmailedAtForSubscriberAction->execute($this->subscriber->id);
    }
}
