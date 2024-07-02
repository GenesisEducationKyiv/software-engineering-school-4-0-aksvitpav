<?php

namespace Tests\Unit\Services;

use App\Interfaces\Adapters\CurrencyRateAdapterInterface;
use App\Services\CurrencyRateService;
use App\VOs\CurrencyErrorRateVO;
use App\VOs\CurrencyRateVO;
use Exception;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class CurrencyRateServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Log::spy();
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_fetches_currency_rate_successfully()
    {
        $adapterMock = $this->createMock(CurrencyRateAdapterInterface::class);
        $rateVO = new CurrencyRateVO(50.08, 50.12);

        $adapterMock->expects($this->once())
            ->method('getCurrencyRate')
            ->willReturn($rateVO);

        $service = new CurrencyRateService([$adapterMock]);
        $rate = $service->getCurrencyRate();

        $this->assertInstanceOf(CurrencyRateVO::class, $rate);
        $this->assertEquals(50.08, $rate->getBuyRate());
        $this->assertEquals(50.12, $rate->getSaleRate());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_error_rate_when_all_providers_fail()
    {
        $adapterMock = $this->createMock(CurrencyRateAdapterInterface::class);

        $adapterMock->expects($this->once())
            ->method('getCurrencyRate')
            ->willThrowException(new Exception('Provider failed'));

        Log::shouldReceive('channel')->with('daily_currency_api')->andReturnSelf();
        Log::shouldReceive('error')->once()->with('Provider failed');

        $service = new CurrencyRateService([$adapterMock]);
        $rate = $service->getCurrencyRate();

        $this->assertInstanceOf(CurrencyErrorRateVO::class, $rate);
        $this->assertStringContainsString('All currency providers failed.', $rate->getError());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_skips_providers_with_error_rate()
    {
        $errorRateVO = new CurrencyErrorRateVO('Provider returned error');

        $adapterMock1 = $this->createMock(CurrencyRateAdapterInterface::class);
        $adapterMock1->expects($this->once())
            ->method('getCurrencyRate')
            ->willReturn($errorRateVO);

        $rateVO = new CurrencyRateVO(50.08, 50.12);

        $adapterMock2 = $this->createMock(CurrencyRateAdapterInterface::class);
        $adapterMock2->expects($this->once())
            ->method('getCurrencyRate')
            ->willReturn($rateVO);

        $service = new CurrencyRateService([$adapterMock1, $adapterMock2]);
        $rate = $service->getCurrencyRate();

        $this->assertInstanceOf(CurrencyRateVO::class, $rate);
        $this->assertEquals(50.08, $rate->getBuyRate());
        $this->assertEquals(50.12, $rate->getSaleRate());
    }
}
