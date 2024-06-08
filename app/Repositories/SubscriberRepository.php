<?php

namespace App\Repositories;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

readonly class SubscriberRepository extends AbstractRepository implements SubscriberRepositoryInterface
{
    /** @inheritDoc */
    public function getModel(): Subscriber
    {
        return new Subscriber();
    }

    /** @inheritDoc */
    public function getQuery(): Builder
    {
        return Subscriber::query();
    }

    /** @inheritDoc */
    public function getNotEmailedSubscribers(Carbon $toDate): Collection
    {
        $query = $this->getQuery();

        return $query
            ->where(function (Builder $query) use ($toDate) {
                $query
                    ->whereNull('subscribers.emailed_at')
                    ->orWhere('subscribers.emailed_at', '<', $toDate);
            })
            ->get();
    }
}
