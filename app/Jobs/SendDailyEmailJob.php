<?php

namespace App\Jobs;

use App\Actions\SetEmailedAtForSubscriberAction;
use App\Interfaces\Jobs\EmailJobInterface;
use App\Mail\CurrentRateMail;
use App\Models\CurrencyRate;
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
     * @param CurrencyRate $currencyRate
     */
    public function __construct(
        public Subscriber $subscriber,
        public CurrencyRate $currencyRate,
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
                USDBuyRate: $this->currencyRate->buy_rate,
                USDSaleRate: $this->currencyRate->sale_rate
            )
        );

        $setEmailedAtForSubscriberAction->execute($this->subscriber->id);
    }
}
