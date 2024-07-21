<?php

namespace Tests\Unit\Repositories;

use App\Models\Subscriber;
use App\Repositories\SubscriberRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class SubscribeRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_get_model()
    {
        $repository = new SubscriberRepository();
        $model = $repository->getModel();

        $this->assertInstanceOf(Subscriber::class, $model);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_get_query()
    {
        $repository = new SubscriberRepository();
        $query = $repository->getQuery();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $query);
    }
}
