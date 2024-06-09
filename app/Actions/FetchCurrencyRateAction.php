<?php

namespace App\Actions;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\VOs\USDRateVO;

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
     * @return USDRateVO
     */
    public function execute(): USDRateVO
    {
        return $this->apiAdapter->getCurrencyRate();
    }
}
