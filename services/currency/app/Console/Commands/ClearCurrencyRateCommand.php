<?php

namespace App\Console\Commands;

use App\Actions\ClearCurrencyRateAction;
use Illuminate\Console\Command;

class ClearCurrencyRateCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:clear-currency-rate';

    /**
     * @var string
     */
    protected $description = 'Delete old currency rate records';

    /**
     * @param ClearCurrencyRateAction $clearCurrencyRateAction
     * @return void
     */
    public function handle(ClearCurrencyRateAction $clearCurrencyRateAction): void
    {
        $clearCurrencyRateAction->execute();
    }
}
