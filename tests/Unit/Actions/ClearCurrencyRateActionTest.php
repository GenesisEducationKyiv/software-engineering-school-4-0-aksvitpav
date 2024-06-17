<?php

namespace Tests\Unit\Actions;

use App\Actions\ClearCurrencyRateAction;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use Mockery;
use Tests\TestCase;

class ClearCurrencyRateActionTest extends TestCase
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_clears_old_currency_rates()
    {
        $now = now();
        $olderThan = $now->subDays(2);

        $mockRepo = Mockery::mock(CurrencyRateRepositoryInterface::class);
        $mockRepo->shouldReceive('clearValuesOlderThan')
            ->set('olderThan', self::equalTo($olderThan))
            ->once();

        $action = new ClearCurrencyRateAction($mockRepo);
        $action->execute();

        $this->assertTrue(true);
    }
}
