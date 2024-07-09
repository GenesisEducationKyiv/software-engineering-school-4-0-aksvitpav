<?php

namespace App\Jobs\MessageBroker;

use App\Actions\SetEmailedAtForSubscriberAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetEmailedAtJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected int $subscriberId,
    ) {
    }

    public function handle(SetEmailedAtForSubscriberAction $setEmailedAtForSubscriber): void
    {
        $setEmailedAtForSubscriber->execute($this->subscriberId);
    }
}
