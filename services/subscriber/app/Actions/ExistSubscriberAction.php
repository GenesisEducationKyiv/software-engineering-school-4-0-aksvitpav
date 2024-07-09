<?php

namespace App\Actions;

use App\DTOs\SubscriberDTO;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;

class ExistSubscriberAction
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
     * @return bool
     */
    public function execute(SubscriberDTO $dto): bool
    {
        return $this->subscriberRepository->exists(['email' => $dto->getEmail()]);
    }
}
