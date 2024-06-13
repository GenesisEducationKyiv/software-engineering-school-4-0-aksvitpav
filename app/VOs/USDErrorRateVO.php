<?php

namespace App\VOs;

use App\Interfaces\VOs\CurrencyRateVOInterface;
use UnexpectedValueException;

class USDErrorRateVO implements CurrencyRateVOInterface
{
    /**
     * @param string $errorMessage
     */
    public function __construct(
        protected string $errorMessage
    ) {
    }

    /**
     * @return float
     */
    public function getBuyRate(): float
    {
        throw new UnexpectedValueException('Cannot get buy rate');
    }

    /**
     * @return float
     */
    public function getSaleRate(): float
    {
        throw new UnexpectedValueException('Cannot get sale rate');
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return true;
    }
}
