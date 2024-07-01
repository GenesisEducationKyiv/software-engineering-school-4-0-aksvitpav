<?php

namespace App\Models;

use App\Casts\CurrencyCast;
use App\Enums\CurrencyCodeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property CurrencyCodeEnum $currency_code
 * @property float $buy_rate
 * @property float $sale_rate
 * @property string $fetched_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereBuyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereFetchedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereSaleRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyRate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_code',
        'buy_rate',
        'sale_rate',
        'fetched_at',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'currency_code' => CurrencyCodeEnum::class,
            'buy_rate' => CurrencyCast::class,
            'sale_rate' => CurrencyCast::class,
            'fetched_at' => 'datetime',
        ];
    }
}
