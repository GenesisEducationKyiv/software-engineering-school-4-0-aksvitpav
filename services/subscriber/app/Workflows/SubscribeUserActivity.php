<?php

namespace App\Workflows;

use App\Actions\SubscribeUserAction;
use App\DTOs\SubscriberDTO;
use App\Exceptions\SubscribtionError;
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

    /**
     * @throws SubscribtionError
     */
    public function execute(SubscribeUserAction $subscribeUser, string $email): void
    {
        $dto = SubscriberDTO::fromArray(['email' => $email]);
        $subscribeUser->execute($dto);

        echo('Subscriber stored: ' . $email);
    }
}
