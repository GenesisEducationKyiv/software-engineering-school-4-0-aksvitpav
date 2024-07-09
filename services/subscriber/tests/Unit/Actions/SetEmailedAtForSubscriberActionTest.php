<?php

namespace Tests\Unit\Actions;

use App\Actions\SetEmailedAtForSubscriberAction;
use App\Interfaces\Repositories\SubscriberRepositoryInterface;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetEmailedAtForSubscriberActionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_sets_emailed_at_for_existing_subscriber()
    {
        $now = now();
        $existingUser = Subscriber::query()->create([
            'id' => 1,
            'email' => 'first@email.com',
            'emailed_at' => $now,
        ]);

        $subscriberId = 1;
        $mockRepo = $this->createMock(SubscriberRepositoryInterface::class);
        $mockRepo->expects($this->once())
            ->method('updateById')
            ->with(
                $subscriberId,
                $this->callback(function ($data) use ($now) {
                    return isset($data['emailed_at']) && $data['emailed_at']->isSameDay($now);
                })
            )
            ->willReturn($existingUser);

        $action = new SetEmailedAtForSubscriberAction($mockRepo);
        $action->execute($subscriberId);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_throws_exception_for_nonexistent_subscriber()
    {
        $now = now();
        $subscriberId = 100;
        $mockRepo = $this->createMock(SubscriberRepositoryInterface::class);
        $mockRepo->expects($this->once())
            ->method('updateById')
            ->with(
                $subscriberId,
                $this->callback(function ($data) use ($now) {
                    return isset($data['emailed_at']) && $data['emailed_at']->isSameDay($now);
                })
            )
            ->willThrowException(new ModelNotFoundException());

        $this->expectException(ModelNotFoundException::class);

        $action = new SetEmailedAtForSubscriberAction($mockRepo);
        $action->execute($subscriberId);
    }
}
