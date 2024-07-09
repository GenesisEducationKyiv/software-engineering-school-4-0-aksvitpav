<?php

namespace App\Interfaces\Services;

use App\Interfaces\VOs\CurrencyRateVOInterface;

interface CurrencyRateServiceInterface
{
    public function getCurrencyRate(): CurrencyRateVOInterface;
}
