<?php

namespace Tests\Unit\Actions;

use App\Actions\FetchCurrencyRateAction;
use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\VOs\CurrencyRateVOInterface;
use Mockery;
use Tests\TestCase;

class FetchCurrencyRateActionTest extends TestCase
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_fetches_currency_rate_from_adapter()
    {
        $mockAdapter = Mockery::mock(CurrencyRateAdapterInterface::class);
        $mockRateVO = Mockery::mock(CurrencyRateVOInterface::class);

        $mockAdapter->shouldReceive('getCurrencyRate')
            ->andReturn($mockRateVO);

        $action = new FetchCurrencyRateAction($mockAdapter, Mockery::mock(CurrencyRateRepositoryInterface::class));
        $fetchedRate = $action->execute();

        $this->assertInstanceOf(CurrencyRateVOInterface::class, $fetchedRate);
    }
}
