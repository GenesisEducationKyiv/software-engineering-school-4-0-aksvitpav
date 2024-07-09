<?php

namespace App\VOs;

use App\Interfaces\VOs\CurrencyRateVOInterface;
use Throwable;
use UnexpectedValueException;

class CurrencyErrorRateVO implements CurrencyRateVOInterface
{
    /**
     * @param string $errorMessage
     * @param Throwable|null $previous
     */
    public function __construct(
        protected string $errorMessage,
        protected ?Throwable $previous = null
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
     * @return Throwable|null
     */
    public function getPrevious(): ?Throwable
    {
        return $this->previous;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return true;
    }
}
