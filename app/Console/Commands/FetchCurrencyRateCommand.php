<?php

namespace App\Console\Commands;

use App\Actions\FetchCurrencyRateAction;
use App\Actions\StoreCurrencyRateAction;
use App\DTOs\CurrencyRateDTO;
use App\Enums\CurrencyCodeEnum;
use App\Interfaces\VOs\CurrencyRateErrorVOInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchCurrencyRateCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:fetch-currency-rate';

    /**
     * @var string
     */
    protected $description = 'Fetch currency rates from API';

    /**
     * @param FetchCurrencyRateAction $fetchCurrencyRate
     * @param StoreCurrencyRateAction $storeCurrencyRateAction
     * @return void
     */
    public function handle(
        FetchCurrencyRateAction $fetchCurrencyRate,
        StoreCurrencyRateAction $storeCurrencyRateAction
    ): void {
        $vo = $fetchCurrencyRate->execute();
        if ($vo instanceof CurrencyRateErrorVOInterface) {
            Log::error('Can\'t fetch currency rate', ['error' => $vo->getError()]);
            return;
        }

        /** @var array{"currency_code":string, "buy_rate": float, "sale_rate": float, "id"?: ?int} $data */
        $data = [
            'currency_code' => CurrencyCodeEnum::USD->value,
            'buy_rate' => $vo->getBuyRate(),
            'sale_rate' => $vo->getSaleRate(),
        ];
        $usdDto = CurrencyRateDTO::fromArray($data);

        $storeCurrencyRateAction->execute($usdDto);
    }
}
