<?php

namespace App\Actions;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;

class DeleteSubscriberAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
    ) {
    }

    /**
     * @param string $email
     * @return void
     */
    public function execute(string $email): void
    {
        $this->subscriberRepository->deleteBy(['email' => $email]);
    }
}
