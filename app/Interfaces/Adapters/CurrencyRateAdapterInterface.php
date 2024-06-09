<?php

namespace App\Interfaces\Adapters;

use App\VOs\USDRateVO;

interface CurrencyRateAdapterInterface
{
    /**
     * @return USDRateVO
     */
    public function getCurrencyRate(): USDRateVO;
}
