<?php

namespace App\Workflows;

use App\Actions\DeleteCustomerAction;
use Workflow\Activity;

class CancelCreateCustomerActivity extends Activity
{
    public $connection = 'rabbitmq';
    public $queue = 'customer';

    public $tries = 1;
    public $timeout = 0;

    public function backoff(): array
    {
        return [0];
    }

    public function execute(DeleteCustomerAction $deleteCustomer, string $email): void
    {
        $deleteCustomer->execute($email);

        echo('Customer storing rollback: ' . $email);
    }
}
