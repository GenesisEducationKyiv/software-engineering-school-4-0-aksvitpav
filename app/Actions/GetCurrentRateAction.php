<?php

namespace App\Actions;

use App\Enums\CurrencyCodeEnum;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

class GetCurrentRateAction
{
    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @return CurrencyRate|Model|null
     */
    public function execute(): Model|CurrencyRate|null
    {
        try {
            return $this->currencyRateRepository->findBy(
                [
                    'currency_code' => CurrencyCodeEnum::USD->value
                ],
                'fetched_at',
                false
            );
        } catch (Throwable $exception) {
            Log::error('Getting rate error', ['message' => $exception->getMessage(), 'code' => $exception->getCode()]);
            return null;
        }
    }
}
