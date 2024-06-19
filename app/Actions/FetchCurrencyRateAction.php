<?php

namespace App\Actions;

use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use App\Services\CurrencyRateService;
use Exception;

class FetchCurrencyRateAction
{
    /**
     * @param CurrencyRateService $currencyRateService
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(
        protected CurrencyRateService $currencyRateService,
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
