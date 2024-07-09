<?php

namespace App\Actions;

use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;

class ClearCurrencyRateAction
{
    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $olderThan = now()->subDays(2);
        $this->currencyRateRepository->clearValuesOlderThan($olderThan);
    }
}
