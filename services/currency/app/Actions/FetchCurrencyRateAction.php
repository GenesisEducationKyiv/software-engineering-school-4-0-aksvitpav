<?php

namespace App\Actions;

use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\Services\CurrencyRateServiceInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use Exception;

class FetchCurrencyRateAction
{
    /**
     * @param CurrencyRateServiceInterface $currencyRateService
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(
        protected CurrencyRateServiceInterface $currencyRateService,
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @return CurrencyRateVOInterface
     * @throws Exception
     */
    public function execute(): CurrencyRateVOInterface
    {
        return $this->currencyRateService->getCurrencyRate();
    }
}
