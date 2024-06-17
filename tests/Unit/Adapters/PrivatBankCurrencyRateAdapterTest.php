<?php

namespace Tests\Unit\Adapters;

use App\Adapters\PrivatBankCurrencyRateAdapter;
use App\Enums\CurrencyCodeEnum;
use App\VOs\USDErrorRateVO;
use App\VOs\USDRateVO;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class PrivatBankCurrencyRateAdapterTest extends TestCase
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_fetches_usd_rates_successfully()
    {
        $mockResponse = new Response(200, [], json_encode([
            [
                'ccy' => CurrencyCodeEnum::USD->value,
                'buy' => 50.08,
                'sale' => 50.12,
            ],
        ]));

        $mockHandler = new MockHandler([$mockResponse]);
        $client = new Client(['handler' => $mockHandler]);

        $adapter = new PrivatBankCurrencyRateAdapter($client);
        $rate = $adapter->getCurrencyRate();

        $this->assertInstanceOf(USDRateVO::class, $rate);
        $this->assertEquals(50.08, $rate->getBuyRate());
        $this->assertEquals(50.12, $rate->getSaleRate());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_error_rate_on_invalid_json_response()
    {
        $mockResponse = new Response(200, [], 'Invalid JSON data');

        $mockHandler = new MockHandler([$mockResponse]);
        $client = new Client(['handler' => $mockHandler]);

        $adapter = new PrivatBankCurrencyRateAdapter($client);
        $rate = $adapter->getCurrencyRate();

        $this->assertInstanceOf(USDErrorRateVO::class, $rate);
        $this->assertStringContainsString('Fetched data has mismatch format.', $rate->getError());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_error_rate_on_missing_usd_rates()
    {
        $mockResponse = new Response(200, [], json_encode([
            [
                'ccy' => 'EUR',
                'buy' => 50.05,
                'sale' => 50.10,
            ],
        ]));

        $mockHandler = new MockHandler([$mockResponse]);
        $client = new Client(['handler' => $mockHandler]);

        $adapter = new PrivatBankCurrencyRateAdapter($client);
        $rate = $adapter->getCurrencyRate();

        $this->assertInstanceOf(USDErrorRateVO::class, $rate);
        $this->assertStringContainsString('Currency rates not found in response.', $rate->getError());
    }
}
