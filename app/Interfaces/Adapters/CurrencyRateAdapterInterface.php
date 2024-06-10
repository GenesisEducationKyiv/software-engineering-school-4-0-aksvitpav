<?php

namespace App\Interfaces\Adapters;

use App\Interfaces\VOs\CurrencyRateVOInterface;

interface CurrencyRateAdapterInterface
{
    /**
     * @return CurrencyRateVOInterface
     */
    public function getCurrencyRate(): CurrencyRateVOInterface;
}
