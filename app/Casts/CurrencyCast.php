<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<float, int>
 */
class CurrencyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, float>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        return $value / 100000;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, float>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        return (int)round($value * 100000);
    }
}
