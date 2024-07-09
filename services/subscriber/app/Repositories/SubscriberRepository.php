<?php

namespace App\Repositories;

use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SubscriberRepository extends AbstractRepository implements SubscriberRepositoryInterface
{
    /** @inheritDoc */
    public function getModel(): Subscriber
    {
        return new Subscriber();
    }

    /**
     * @return Builder<Subscriber>
     */
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
                    ->where('subscribers.is_active', true)
                    ->whereNull('subscribers.emailed_at')
                    ->orWhere('subscribers.emailed_at', '<', $toDate);
            })
            ->get();
    }
}
