<?php

namespace App\Workflows;

use Workflow\Activity;

class CreateCustomerActivity extends Activity
{
    public $connection = 'rabbitmq';
    public $queue = 'customer';

    public $tries = 1;
    public $timeout = 0;

    public function backoff(): array
    {
        return [0];
    }
}
