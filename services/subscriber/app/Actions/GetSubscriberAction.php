<?php

namespace App\Actions;

use App\DTOs\SubscriberDTO;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;

class GetSubscriberAction
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
     * @return Subscriber|null
     */
    public function execute(SubscriberDTO $dto): ?Subscriber
    {
        /** @var Subscriber|null $result */
        $result = $this->subscriberRepository->findBy(['email' => $dto->getEmail()]);

        return $result;
    }
}
