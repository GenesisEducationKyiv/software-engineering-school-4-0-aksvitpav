<?php

namespace App\Repositories;

use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

readonly class CurrencyRateRepository extends AbstractRepository implements CurrencyRateRepositoryInterface
{
    /**
     * @return CurrencyRate
     */
    public function getModel(): CurrencyRate
    {
        return new CurrencyRate();
    }

    /**
     * @return Builder<CurrencyRate>
     */
    public function getQuery(): Builder
    {
        return CurrencyRate::query();
    }

    /** @inheritDoc */
    public function clearValuesOlderThan(Carbon $olderThan): void
    {
        $query = $this->getQuery();

        $query
            ->where('fetched_at', '<=', $olderThan)
            ->delete();
    }
}
