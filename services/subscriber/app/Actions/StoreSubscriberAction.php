<?php

namespace App\Actions;

use App\DTOs\SubscriberDTO;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Model;

class StoreSubscriberAction
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
     * @return Subscriber|Model
     */
    public function execute(SubscriberDTO $dto): Subscriber|Model
    {
        return $this->subscriberRepository->create($dto->toArray());
    }
}
