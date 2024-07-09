<?php

namespace Tests\Feature;

use App\Actions\ExistSubscriberAction;
use App\Actions\StoreSubscriberAction;
use App\DTOs\SubscriberDTO;
use App\Models\Subscriber;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_subscribes_email_successfully(): void
    {
        $email = 'test@example.com';
        $requestData = ['email' => $email];

        $dtoMatcher = Mockery::on(function ($dto) use ($requestData) {
            return $dto instanceof SubscriberDTO && $dto->getEmail() === $requestData['email'];
        });

        $existSubscriberActionMock = Mockery::mock(ExistSubscriberAction::class);
        $existSubscriberActionMock->shouldReceive('execute')
            ->with($dtoMatcher)
            ->andReturn(false);

        $storeSubscriberActionMock = Mockery::mock(StoreSubscriberAction::class);
        $storeSubscriberActionMock->shouldReceive('execute')
            ->with($dtoMatcher)
            ->andReturn(new Subscriber(['email' => $email]));

        $this->app->instance(ExistSubscriberAction::class, $existSubscriberActionMock);
        $this->app->instance(StoreSubscriberAction::class, $storeSubscriberActionMock);

        $response = $this->postJson('/api/subscribe', $requestData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Email successfully added'
            ]);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_conflict_when_email_already_exists(): void
    {
        $email = 'test@example.com';
        $requestData = ['email' => $email];

        $dtoMatcher = Mockery::on(function ($dto) use ($requestData) {
            return $dto instanceof SubscriberDTO && $dto->getEmail() === $requestData['email'];
        });

        $existSubscriberActionMock = Mockery::mock(ExistSubscriberAction::class);
        $existSubscriberActionMock->shouldReceive('execute')
            ->with($dtoMatcher)
            ->andReturn(true);

        $storeSubscriberActionMock = Mockery::mock(StoreSubscriberAction::class);
        $storeSubscriberActionMock->shouldNotReceive('execute');

        $this->app->instance(ExistSubscriberAction::class, $existSubscriberActionMock);
        $this->app->instance(StoreSubscriberAction::class, $storeSubscriberActionMock);

        $response = $this->postJson('/api/subscribe', $requestData);

        $response->assertStatus(Response::HTTP_CONFLICT)
            ->assertJson([
                'message' => 'Email already exists'
            ]);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_returns_validation_error_for_invalid_email(): void
    {
        $invalidEmail = 'invalid-email';
        $requestData = ['email' => $invalidEmail];

        $response = $this->postJson('/api/subscribe', $requestData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
