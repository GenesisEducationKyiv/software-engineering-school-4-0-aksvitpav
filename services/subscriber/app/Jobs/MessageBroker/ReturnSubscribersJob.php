<?php

namespace App\Jobs\MessageBroker;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReturnSubscribersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        /** @var array{"saleRate":float, "buyRate":float} $currencyRate */
        protected array $currencyRate,
        /** @var array{array{"id":int, "email":string}} $subscribers */
        protected array $subscribers
    ) {
    }

    public function handle(): void
    {
    }
}
