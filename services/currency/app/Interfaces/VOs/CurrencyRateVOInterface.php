<?php

namespace App\Interfaces\VOs;

interface CurrencyRateVOInterface
{
    public function getBuyRate(): float;
    public function getSaleRate(): float;
    public function getError(): string;
    public function hasError(): bool;
}
