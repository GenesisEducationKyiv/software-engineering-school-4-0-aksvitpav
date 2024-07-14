<?php

namespace App\Workflows;

use App\Actions\DeleteSubscriberAction;
use Workflow\Activity;

class CancelSubscribeUserActivity extends Activity
{
    public $connection = 'rabbitmq';
    public $queue = 'subscriber';

    public $tries = 1;
    public $timeout = 0;

    public function backoff(): array
    {
        return [0];
    }

    public function execute(DeleteSubscriberAction $deleteSubscriber, string $email): void
    {
        $deleteSubscriber->execute($email);
    }
}
