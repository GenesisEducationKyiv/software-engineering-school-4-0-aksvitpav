<?php

namespace App\Interfaces\Repositories;

use Illuminate\Support\Carbon;

interface CurrencyRateRepositoryInterface extends RepositoryInterface
{
    /**
     * @param Carbon $olderThan
     * @return void
     */
    public function clearValuesOlderThan(Carbon $olderThan): void;
}
