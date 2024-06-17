<?php

namespace App\Actions;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\VOs\CurrencyRateErrorVOInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;

class FetchCurrencyRateAction
{
    /**
     * @param CurrencyRateAdapterInterface $apiAdapter
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(
        protected CurrencyRateAdapterInterface $apiAdapter,
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @return CurrencyRateVOInterface|CurrencyRateErrorVOInterface
     */
    public function execute(): CurrencyRateVOInterface|CurrencyRateErrorVOInterface
    {
        return $this->apiAdapter->getCurrencyRate();
    }
}
