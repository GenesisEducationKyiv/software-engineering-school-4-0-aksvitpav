<?php

namespace Tests\Unit\Actions;

use App\Actions\StoreSubscriberAction;
use App\DTOs\SubscriberDTO;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Mockery;
use Tests\TestCase;

class StoreSubscriberActionTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_execute_stores_subscriber()
    {
        $subscriberData = ['email' => 'test@example.com'];
        $dto = new SubscriberDTO($subscriberData['email']);

        $subscriber = new Subscriber();
        $subscriber->fill($subscriberData);

        $repository = Mockery::mock(SubscriberRepositoryInterface::class);
        $repository->shouldReceive('create')->once()->andReturn($subscriber);

        $action = new StoreSubscriberAction($repository);

        $result = $action->execute($dto);

        $this->assertInstanceOf(Subscriber::class, $result);
        $this->assertEquals($subscriber->email, $result->email);
    }
}
