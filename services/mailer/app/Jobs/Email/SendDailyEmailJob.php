<?php

namespace App\Jobs\Email;

use App\Interfaces\Jobs\EmailJobInterface;
use App\Jobs\MessageBroker\SetEmailedAtJob;
use App\Mail\CurrentRateMail;
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
     * @param string $subscriberId
     * @param string $subscriberEmail
     * @param float $USDBuyRate
     * @param float $USDSaleRate
     */
    public function __construct(
        public string $subscriberId,
        public string $subscriberEmail,
        public float $USDBuyRate,
        public float $USDSaleRate,
    ) {
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        Mail::to($this->subscriberEmail)->send(
            new CurrentRateMail(
                USDBuyRate: $this->USDBuyRate,
                USDSaleRate: $this->USDSaleRate,
            )
        );

        SetEmailedAtJob::dispatch($this->subscriberId)->onQueue('subscriber');
    }
}
