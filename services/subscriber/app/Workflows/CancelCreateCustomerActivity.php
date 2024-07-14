<?php

namespace App\Workflows;

use Workflow\Activity;

class CancelCreateCustomerActivity extends Activity
{
    public $connection = 'rabbitmq';
    public $queue = 'customer';
}
