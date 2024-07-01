<?php

namespace App\Actions;

use App\DTOs\SubscriberDTO;
use App\Exceptions\SubscribtionError;
use Illuminate\Support\Facades\Log;
use Throwable;

class SubscribeUserAction
{
    /**
     * @param ExistSubscriberAction $existSubscriberAction
     * @param StoreSubscriberAction $storeSubscriberAction
     */
    public function __construct(
        protected ExistSubscriberAction $existSubscriberAction,
        protected StoreSubscriberAction $storeSubscriberAction,
    ) {
    }

    /**
     * @param SubscriberDTO $dto
     * @return bool
     * @throws SubscribtionError
     */
    public function execute(SubscriberDTO $dto): bool
    {
        try {
            $isSubscriberExists = $this->existSubscriberAction->execute($dto);

            if ($isSubscriberExists) {
                return false;
            }

            $this->storeSubscriberAction->execute($dto);
            return true;
        } catch (Throwable $exception) {
            Log::error('Subscribe error', ['message' => $exception->getMessage(), 'code' => $exception->getCode()]);
            throw new SubscribtionError('An error occurred while completing your subscription');
        }
    }
}
