<?php

namespace Tests\Unit\Adapters;

use App\Adapters\ABankCurrencyRateAdapter;
use App\VOs\CurrencyErrorRateVO;
use App\VOs\CurrencyRateVO;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class ABankCurrencyRateAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['circuit-breaker.driver' => 'array']);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_fetches_usd_rates_successfully()
    {
        $mockResponse = new Response(200, [], json_encode([
            'data' => [
                [
                    'curA' => 'UAH',
                    'curB' => 'USD',
                    'rate_sell' => 50.12,
                    'rate_buy' => 50.08,
                ],
            ],
            'status' => 'success',
            'result' => 'OK',
            'timestamp' => '2024-06-19T12:00:00Z',
            'request_ref' => 'abc123',
            'response_ref' => 'xyz789',
        ]));

        $mockHandler = new MockHandler([$mockResponse]);
        $client = new Client(['handler' => $mockHandler]);

        $adapter = new ABankCurrencyRateAdapter($client);
        $rate = $adapter->getCurrencyRate();

        $this->assertInstanceOf(CurrencyRateVO::class, $rate);
        $this->assertEquals(50.08, $rate->getBuyRate());
        $this->assertEquals(50.12, $rate->getSaleRate());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_error_rate_on_invalid_json_response()
    {
        $mockResponse = new Response(200, [], 'Invalid JSON data');

        $mockHandler = new MockHandler([$mockResponse]);
        $client = new Client(['handler' => $mockHandler]);

        $adapter = new ABankCurrencyRateAdapter($client);
        $rate = $adapter->getCurrencyRate();

        $this->assertInstanceOf(CurrencyErrorRateVO::class, $rate);
        $this->assertStringContainsString('Fetched data has mismatch format.', $rate->getError());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_error_rate_on_missing_usd_rates()
    {
        $mockResponse = new Response(200, [], json_encode([
            [
                'data' => [
                    [
                        'curA' => 'UAH',
                        'curB' => 'USD',
                        'rate_sell' => 50.12,
                        'rate_buy' => 50.08,
                    ],
                ],
                'status' => 'success',
                'result' => 'OK',
                'timestamp' => '2024-06-19T12:00:00Z',
                'request_ref' => 'abc123',
                'response_ref' => 'xyz789',
            ],
        ]));

        $mockHandler = new MockHandler([$mockResponse]);
        $client = new Client(['handler' => $mockHandler]);

        $adapter = new ABankCurrencyRateAdapter($client);
        $rate = $adapter->getCurrencyRate();

        $this->assertInstanceOf(CurrencyErrorRateVO::class, $rate);
        $this->assertStringContainsString('Currency rates not found in response.', $rate->getError());
    }
}
