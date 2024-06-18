<?php

namespace Database\Factories;

use App\Enums\CurrencyCodeEnum;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CurrencyRateFactory extends Factory
{
    protected $model = CurrencyRate::class;

    public function definition(): array
    {
        return [
            'currency_code' => CurrencyCodeEnum::USD,
            'buy_rate' => $this->faker->randomFloat(4, 40, 50),
            'sale_rate' => $this->faker->randomFloat(4, 40, 50),
            'fetched_at' => Carbon::parse($this->faker->dateTime),
        ];
    }
}
