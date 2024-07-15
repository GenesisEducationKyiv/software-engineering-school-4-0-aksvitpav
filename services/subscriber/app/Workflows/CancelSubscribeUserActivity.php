<?php

namespace App\Workflows;

use App\Actions\DeleteSubscriberAction;
use Workflow\Activity;

class CancelSubscribeUserActivity extends Activity
{
    public $connection = 'rabbitmq';
    public $queue = 'subscriber';

    /** @var int $tries */
    public $tries = 1;
    /** @var int $timeout */
    public $timeout = 0;

    /** @return int[] */
    public function backoff(): array
    {
        return [0];
    }

    public function execute(DeleteSubscriberAction $deleteSubscriber, string $email): void
    {
        $deleteSubscriber->execute($email);
    }
}
