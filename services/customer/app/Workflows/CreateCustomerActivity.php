<?php

namespace App\Workflows;

use App\Actions\ExistCustomerAction;
use App\Actions\StoreCustomerAction;
use App\Actions\UpdateCustomerAction;
use App\DTOs\CustomerDTO;
use Workflow\Activity;

class CreateCustomerActivity extends Activity
{
    public $connection = 'rabbitmq';
    public $queue = 'customer';

    /** @var int $tries */
    public $tries = 1;
    /** @var int $timeout */
    public $timeout = 0;

    /** @return int[] */
    public function backoff(): array
    {
        return [0];
    }

    public function execute(
        ExistCustomerAction $existCustomer,
        StoreCustomerAction $storeCustomer,
        UpdateCustomerAction $updateCustomer,
        string $email
    ): void {
        $dto = CustomerDTO::fromArray(['email' => $email]);

        if (!$existCustomer->execute($dto)) {
            $storeCustomer->execute($dto);
        } else {
            $updateCustomer->execute($dto);
        }

        echo('Customer stored: ' . $email);
    }
}