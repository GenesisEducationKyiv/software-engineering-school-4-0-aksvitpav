<?php

namespace App\Workflows;

use Generator;
use Throwable;
use Workflow\ActivityStub;
use Workflow\Workflow;

class SubscribeSaga extends Workflow
{
    public $connection = 'rabbitmq';
    public $queue = 'subscriber';

    public int $tries = 0;
    public int $timeout = 0;

    /** @return int[] */
    public function backoff(): array
    {
        return [0];
    }

    public function execute(string $email): bool|Generator
    {
        try {
            yield ActivityStub::make(SubscribeUserActivity::class, $email); // @phpstan-ignore-line
            yield ActivityStub::make(CreateCustomerActivity::class, $email); // @phpstan-ignore-line
            return true;
        } catch (Throwable $exception) {
            yield ActivityStub::make(CancelCreateCustomerActivity::class, $email); // @phpstan-ignore-line
            yield ActivityStub::make(CancelSubscribeUserActivity::class, $email); // @phpstan-ignore-line
            return false;
        }
    }
}
