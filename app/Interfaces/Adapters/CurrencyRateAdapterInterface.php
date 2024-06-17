<?php

namespace App\Interfaces\Adapters;

use App\Interfaces\VOs\CurrencyRateErrorVOInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;

interface CurrencyRateAdapterInterface
{
    /**
     * @return CurrencyRateVOInterface|CurrencyRateErrorVOInterface
     */
    public function getCurrencyRate(): CurrencyRateVOInterface|CurrencyRateErrorVOInterface;
}
