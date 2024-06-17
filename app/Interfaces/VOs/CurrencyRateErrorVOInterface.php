<?php

namespace App\Interfaces\VOs;

interface CurrencyRateErrorVOInterface
{
    public function getError(): string;
    public function hasError(): bool;
}
