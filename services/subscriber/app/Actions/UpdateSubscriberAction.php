<?php

namespace App\Actions;

use App\DTOs\SubscriberDTO;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;

class UpdateSubscriberAction
{
    /**
     * @param SubscriberRepositoryInterface $subscriberRepository
     */
    public function __construct(
        protected SubscriberRepositoryInterface $subscriberRepository,
    ) {
    }

    /**
     * @param SubscriberDTO $dto
     * @return void
     */
    public function execute(SubscriberDTO $dto): void
    {
        $this->subscriberRepository->updateBy(['email' => $dto->getEmail()], $dto->toArray());
    }
}
