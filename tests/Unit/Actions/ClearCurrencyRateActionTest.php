<?php

namespace Tests\Unit\Actions;

use App\Actions\ClearCurrencyRateAction;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearCurrencyRateActionTest extends TestCase
{
    use RefreshDatabase;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_clears_old_currency_rates()
    {
        $now = now();

        CurrencyRate::factory()->create(['fetched_at' => $now->clone()->subDays(3)]);
        CurrencyRate::factory()->create(['fetched_at' => $now->clone()->subDay()]);

        $repository = app(CurrencyRateRepositoryInterface::class);
        $action = new ClearCurrencyRateAction($repository);

        $action->execute();

        $this->assertDatabaseMissing('currency_rates', ['fetched_at' => $now->clone()->subDays(3)]);
        $this->assertDatabaseHas('currency_rates', ['fetched_at' => $now->clone()->subDay()]);
    }
}
