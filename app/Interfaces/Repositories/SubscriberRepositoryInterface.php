<?php

namespace App\Interfaces\Repositories;

use App\Models\Subscriber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface SubscriberRepositoryInterface extends RelationRepositoryInterface
{
    /**
     * @param Carbon $toDate
     * @return Collection<int, Subscriber>
     */
    public function getNotEmailedSubscribers(Carbon $toDate): Collection;
}
