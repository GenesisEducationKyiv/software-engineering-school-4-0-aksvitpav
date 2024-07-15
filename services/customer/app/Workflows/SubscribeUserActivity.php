<?php

namespace App\Workflows;

use Workflow\Activity;

class SubscribeUserActivity extends Activity
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
}
