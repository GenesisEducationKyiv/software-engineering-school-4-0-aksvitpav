<?php

namespace Tests\Unit\Commands;

use App\Actions\ClearCurrencyRateAction;
use App\Console\Commands\ClearCurrencyRateCommand;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class ClearCurrencyRateCommandTest extends TestCase
{
    use WithFaker;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_calls_clear_currency_rate_action()
    {
        $mockAction = Mockery::mock(ClearCurrencyRateAction::class);
        $mockAction->shouldReceive('execute')->once();

        $this->app->instance(ClearCurrencyRateAction::class, $mockAction);
        $command = new ClearCurrencyRateCommand();

        $command->handle($mockAction);

        $this->assertTrue(true);
    }
}
