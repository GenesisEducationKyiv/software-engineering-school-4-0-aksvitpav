<?php

namespace Tests\Unit\Actions;

use App\Actions\ExistSubscriberAction;
use App\Actions\StoreSubscriberAction;
use App\Actions\SubscribeUserAction;
use App\DTOs\SubscriberDTO;
use App\Exceptions\SubscribtionError;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class SubscribeUserActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Log::shouldReceive('error')->andReturnNull();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_execute_subscriber_exists()
    {
        $existSubscriberAction = Mockery::mock(ExistSubscriberAction::class);
        $existSubscriberAction->shouldReceive('execute')->andReturn(true);

        $storeSubscriberAction = Mockery::mock(StoreSubscriberAction::class);
        $storeSubscriberAction->shouldNotReceive('execute');

        $action = new SubscribeUserAction($existSubscriberAction, $storeSubscriberAction);

        $dto = new SubscriberDTO('existing@example.com');

        $this->assertFalse($action->execute($dto));
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_execute_subscriber_does_not_exist()
    {
        $existSubscriberAction = Mockery::mock(ExistSubscriberAction::class);
        $existSubscriberAction->shouldReceive('execute')->andReturn(false);

        $storeSubscriberAction = Mockery::mock(StoreSubscriberAction::class);
        $storeSubscriberAction->shouldReceive('execute')->once();

        $action = new SubscribeUserAction($existSubscriberAction, $storeSubscriberAction);

        $dto = new SubscriberDTO('new@example.com');

        $this->assertTrue($action->execute($dto));
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_execute_throws_error()
    {
        $existSubscriberAction = Mockery::mock(ExistSubscriberAction::class);
        $existSubscriberAction->shouldReceive('execute')->andThrow(new \Exception('Test Exception', 500));

        $storeSubscriberAction = Mockery::mock(StoreSubscriberAction::class);
        $storeSubscriberAction->shouldNotReceive('execute');

        $action = new SubscribeUserAction($existSubscriberAction, $storeSubscriberAction);

        $dto = new SubscriberDTO('test@example.com');

        $this->expectException(SubscribtionError::class);
        $this->expectExceptionMessage('An error occurred while completing your subscription');

        $action->execute($dto);
    }
}
