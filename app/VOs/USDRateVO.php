<?php

namespace App\VOs;

use App\Interfaces\VOs\CurrencyRateErrorVOInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use UnexpectedValueException;

class USDRateVO implements CurrencyRateVOInterface, CurrencyRateErrorVOInterface
{
    /**
     * @param float $buyRate
     * @param float $saleRate
     */
    public function __construct(
        protected float $buyRate,
        protected float $saleRate
    ) {
    }

    /**
     * @return float
     */
    public function getBuyRate(): float
    {
        return $this->buyRate;
    }

    /**
     * @return float
     */
    public function getSaleRate(): float
    {
        return $this->saleRate;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        throw new UnexpectedValueException('No error present.');
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return false;
    }
}
