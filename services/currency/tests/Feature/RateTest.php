<?php

namespace Tests\Feature;

use App\Actions\GetCurrentRateAction;
use App\Enums\CurrencyCodeEnum;
use App\Models\CurrencyRate;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RateTest extends TestCase
{
    //use RefreshDatabase;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_current_rate_successfully(): void
    {
        $currentRate = new CurrencyRate(
            [
                'currency_code' => CurrencyCodeEnum::USD->value,
                'buy_rate' => 38.5,
                'sale_rate' => 39.5,
                'fetched_at' => now(),
            ]
        );

        $getCurrentRateActionMock = Mockery::mock(GetCurrentRateAction::class);
        $getCurrentRateActionMock->shouldReceive('execute')->andReturn($currentRate);

        $this->app->instance(GetCurrentRateAction::class, $getCurrentRateActionMock);

        $response = $this->getJson('/api/rate');

        $response->assertStatus(200)
            ->assertJson([
                'buy' => 38.50,
                'sale' => 39.50
            ]);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_bad_request_when_rate_is_invalid(): void
    {
        $getCurrentRateActionMock = Mockery::mock(GetCurrentRateAction::class);
        $getCurrentRateActionMock->shouldReceive('execute')->andReturn(null);

        $this->app->instance(GetCurrentRateAction::class, $getCurrentRateActionMock);

        $response = $this->getJson('/api/rate');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'message' => 'Invalid status value'
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
