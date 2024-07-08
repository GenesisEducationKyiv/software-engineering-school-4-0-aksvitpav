<?php

namespace App\Actions;

use App\DTOs\SubscriberDTO;
use App\Exceptions\SubscribtionError;
use Illuminate\Support\Facades\Log;
use Throwable;

class UnsubscribeUserAction
{
    /**
     * @param GetSubscriberAction $getSubscriberAction
     * @param UpdateSubscriberAction $updateSubscriberAction
     */
    public function __construct(
        protected GetSubscriberAction $getSubscriberAction,
        protected UpdateSubscriberAction $updateSubscriberAction,
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
            $subscriber = $this->getSubscriberAction->execute($dto);

            if (! $subscriber?->is_active) {
                return false;
            }

            $this->updateSubscriberAction->execute($dto);
            return true;
        } catch (Throwable $exception) {
            Log::error('Unsubscribe error', ['message' => $exception->getMessage(), 'code' => $exception->getCode()]);
            throw new SubscribtionError('An error occurred while completing your unsubscription');
        }
    }
}
