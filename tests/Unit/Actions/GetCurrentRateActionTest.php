<?php

namespace Tests\Unit\Actions;

use App\Actions\GetCurrentRateAction;
use App\Enums\CurrencyCodeEnum;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class GetCurrentRateActionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_gets_current_usd_rate_from_repository()
    {
        $now = now();
        $currencyRate = new CurrencyRate([
            'currency_code' => CurrencyCodeEnum::USD->value,
            'buy_rate' => 1.05,
            'sale_rate' => 1.10,
            'fetched_at' => $now,
        ]);

        $mockRepo = Mockery::mock(CurrencyRateRepositoryInterface::class);
        $mockRepo->shouldReceive('findBy')
            ->with([
                'currency_code' => CurrencyCodeEnum::USD->value,
            ], 'fetched_at', false)
            ->andReturn($currencyRate);

        $action = new GetCurrentRateAction($mockRepo);
        $returnedRate = $action->execute();

        $this->assertInstanceOf(CurrencyRate::class, $returnedRate);
        $this->assertEquals($currencyRate->currency_code, $returnedRate->currency_code);
        $this->assertEquals($currencyRate->buy_rate, $returnedRate->buy_rate);
        $this->assertEquals($currencyRate->sale_rate, $returnedRate->sale_rate);
        $this->assertEquals($currencyRate->fetched_at, $returnedRate->fetched_at);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_null_on_repository_exception()
    {
        $mockRepo = Mockery::mock(CurrencyRateRepositoryInterface::class);
        $mockRepo->shouldReceive('findBy')
            ->andThrow(new Exception());

        $action = new GetCurrentRateAction($mockRepo);
        $returnedRate = $action->execute();

        $this->assertNull($returnedRate);
    }
}
