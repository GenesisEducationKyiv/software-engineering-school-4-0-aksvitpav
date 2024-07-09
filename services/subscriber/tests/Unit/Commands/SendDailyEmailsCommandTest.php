<?php

namespace Tests\Unit\Commands;

use App\Actions\GetNotEmailedSubscribersAction;
use App\Console\Commands\SendDailyEmailsCommand;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class SendDailyEmailsCommandTest extends TestCase
{
    use WithFaker;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_calls_send_daily_emails_action()
    {
        $mockAction = Mockery::mock(GetNotEmailedSubscribersAction::class);
        $mockAction->shouldReceive('execute')->once();

        $this->app->instance(GetNotEmailedSubscribersAction::class, $mockAction);
        $command = new SendDailyEmailsCommand();

        $command->handle($mockAction);

        $this->assertTrue(true);
    }
}
