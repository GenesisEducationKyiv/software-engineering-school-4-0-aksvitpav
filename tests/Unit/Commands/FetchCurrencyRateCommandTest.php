<?php

namespace Tests\Unit\Commands;

use App\Actions\FetchCurrencyRateAction;
use App\Actions\StoreCurrencyRateAction;
use App\Console\Commands\FetchCurrencyRateCommand;
use App\DTOs\CurrencyRateDTO;
use App\Enums\CurrencyCodeEnum;
use App\VOs\CurrencyErrorRateVO;
use App\VOs\CurrencyRateVO;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class FetchCurrencyRateCommandTest extends TestCase
{
    use WithFaker;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_fetches_and_stores_currency_rate_successfully()
    {
        $mockFetchAction = Mockery::mock(FetchCurrencyRateAction::class);
        $mockStoreAction = Mockery::mock(StoreCurrencyRateAction::class);

        $buyRate = $this->faker->randomFloat(50.01, 49.2, 50.20);
        $saleRate = $this->faker->randomFloat(50.01, 49.2, 50.20);

        $mockFetchAction->shouldReceive('execute')
            ->once()
            ->andReturn(
                new CurrencyRateVO(
                    buyRate: $buyRate,
                    saleRate: $saleRate
                )
            );

        $mockStoreAction->shouldReceive('execute')
            ->once()
            ->with(
                Mockery::on(function (CurrencyRateDTO $dto) use ($buyRate, $saleRate) {
                    return $dto->getCurrencyCode() === CurrencyCodeEnum::USD->value &&
                        $dto->getBuyRate() === $buyRate &&
                        $dto->getSaleRate() === $saleRate;
                })
            );

        $this->app->instance(FetchCurrencyRateAction::class, $mockFetchAction);
        $this->app->instance(StoreCurrencyRateAction::class, $mockStoreAction);

        $command = new FetchCurrencyRateCommand();
        $command->handle($mockFetchAction, $mockStoreAction);

        $this->assertTrue(true);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_logs_error_on_fetch_failure()
    {
        $mockFetchAction = Mockery::mock(FetchCurrencyRateAction::class);
        $mockStoreAction = Mockery::mock(StoreCurrencyRateAction::class);

        $errorMessage = 'API error: Fetching failed.';
        $mockFetchAction->shouldReceive('execute')
            ->once()
            ->andReturn(
                new CurrencyErrorRateVO(
                    errorMessage: $errorMessage
                )
            );

        $mockLog = Mockery::mock();
        $mockLog->shouldReceive('error')
            ->once()
            ->with('Can\'t fetch currency rate', ['error' => $errorMessage]);

        Log::shouldReceive('channel')
            ->andReturn($mockLog);

        $this->app->instance(FetchCurrencyRateAction::class, $mockFetchAction);
        $this->app->instance(StoreCurrencyRateAction::class, $mockStoreAction);

        $command = new FetchCurrencyRateCommand();
        $command->handle($mockFetchAction, $mockStoreAction);

        $this->assertTrue(true);
    }
}
