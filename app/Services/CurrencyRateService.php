<?php

namespace App\Services;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use App\VOs\CurrencyErrorRateVO;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class CurrencyRateService
{
    /**
     * @param array<CurrencyRateAdapterInterface> $providers
     */
    public function __construct(protected array $providers)
    {
    }

    /**
     * @throws Exception
     */
    public function getCurrencyRate(): CurrencyRateVOInterface
    {
        foreach ($this->providers as $provider) {
            try {
                $rate = $provider->getCurrencyRate();
                if (!$rate->hasError()) {
                    return $rate;
                }
            } catch (Throwable $e) {
                Log::channel('daily_currency_api')->error($e->getMessage());
            }
        }

        return new CurrencyErrorRateVO('All currency providers failed.');
    }
}
