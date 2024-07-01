<?php

namespace App\Actions;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;

class SetEmailedAtForSubscriberAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
    ) {
    }

    /**
     * @param int $subscriberId
     * @return void
     */
    public function execute(int $subscriberId): void
    {
        $this->subscriberRepository->updateById(
            $subscriberId,
            [
                'emailed_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
