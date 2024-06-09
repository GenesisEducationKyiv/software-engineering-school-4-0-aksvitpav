<?php

namespace App\VOs;

class USDRateVO
{
    /**
     * @param float|null $buyRate
     * @param float|null $saleRate
     * @param string|null $error
     */
    public function __construct(
        protected ?float $buyRate = null,
        protected ?float $saleRate = null,
        protected ?string $error = null
    ) {
    }

    /**
     * @return float|null
     */
    public function getBuyRate(): ?float
    {
        return $this->buyRate;
    }

    /**
     * @return float|null
     */
    public function getSaleRate(): ?float
    {
        return $this->saleRate;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return (bool)$this->error;
    }
}
