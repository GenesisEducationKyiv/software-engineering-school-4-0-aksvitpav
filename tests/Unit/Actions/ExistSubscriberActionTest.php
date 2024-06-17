<?php

namespace Tests\Unit\Actions;

use App\Actions\ExistSubscriberAction;
use App\DTOs\SubscriberDTO;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use Mockery;
use Tests\TestCase;

class ExistSubscriberActionTest extends TestCase
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_checks_subscriber_existence_by_email()
    {
        $email = 'test@example.com';
        $dto = new SubscriberDTO($email);

        $mockRepo = Mockery::mock(SubscriberRepositoryInterface::class);
        $mockRepo->shouldReceive('exists')
            ->with(['email' => $email])
            ->andReturn(true);

        $action = new ExistSubscriberAction($mockRepo);
        $exists = $action->execute($dto);

        $this->assertTrue($exists);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_false_if_subscriber_not_found()
    {
        $email = 'not-existing@example.com';
        $dto = new SubscriberDTO($email);

        $mockRepo = Mockery::mock(SubscriberRepositoryInterface::class);
        $mockRepo->shouldReceive('exists')
            ->with(['email' => $email])
            ->andReturn(false);

        $action = new ExistSubscriberAction($mockRepo);
        $exists = $action->execute($dto);

        $this->assertFalse($exists);
    }
}
