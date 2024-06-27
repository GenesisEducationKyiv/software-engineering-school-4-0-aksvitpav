<?php

namespace App\Interfaces\Adapters;

use App\Interfaces\VOs\CurrencyRateVOInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

interface CurrencyRateAdapterInterface
{
    /**
     * @return CurrencyRateVOInterface
     * @throws BindingResolutionException
     */
    public function getCurrencyRate(): CurrencyRateVOInterface;
}
