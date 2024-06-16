<?php

namespace Tests\Unit\Repositories;

use App\Models\Subscriber;
use App\Repositories\SubscriberRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_get_not_emailed_subscribers()
    {
        $toDate = Carbon::now()->subDays(30);

        $mockQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $mockQuery->shouldReceive('where')
            ->with(Mockery::on(function ($closure) use ($toDate) {
                $subQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                $subQuery->shouldReceive('whereNull')
                    ->with('subscribers.emailed_at')
                    ->andReturnSelf();
                $subQuery->shouldReceive('orWhere')
                    ->with('subscribers.emailed_at', '<', $toDate)
                    ->andReturnSelf();

                $closure($subQuery);

                return true;
            }))
            ->andReturnSelf();
        $mockQuery->shouldReceive('get')
            ->andReturn(new Collection());

        $repository = Mockery::mock(SubscriberRepository::class)->makePartial();
        $repository->shouldReceive('getQuery')
            ->andReturn($mockQuery);

        $result = $repository->getNotEmailedSubscribers($toDate);

        $this->assertInstanceOf(Collection::class, $result);
        $mockQuery->shouldHaveReceived('where')->once();
        $mockQuery->shouldHaveReceived('get')->once();
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_get_not_emailed_subscribers_with_no_records()
    {
        $toDate = Carbon::now()->subDays(30);

        $mockQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $mockQuery->shouldReceive('where')
            ->with(Mockery::on(function ($closure) use ($toDate) {
                $subQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                $subQuery->shouldReceive('whereNull')
                    ->with('subscribers.emailed_at')
                    ->andReturnSelf();
                $subQuery->shouldReceive('orWhere')
                    ->with('subscribers.emailed_at', '<', $toDate)
                    ->andReturnSelf();

                $closure($subQuery);

                return true;
            }))
            ->andReturnSelf();
        $mockQuery->shouldReceive('get')
            ->andReturn(new Collection());

        $repository = Mockery::mock(SubscriberRepository::class)->makePartial();
        $repository->shouldReceive('getQuery')
            ->andReturn($mockQuery);

        $result = $repository->getNotEmailedSubscribers($toDate);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
        $mockQuery->shouldHaveReceived('where')->once();
        $mockQuery->shouldHaveReceived('get')->once();
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_get_not_emailed_subscribers_with_future_date()
    {
        $toDate = Carbon::now()->addDays(30);

        $mockQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $mockQuery->shouldReceive('where')
            ->with(Mockery::on(function ($closure) use ($toDate) {
                $subQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
                $subQuery->shouldReceive('whereNull')
                    ->with('subscribers.emailed_at')
                    ->andReturnSelf();
                $subQuery->shouldReceive('orWhere')
                    ->with('subscribers.emailed_at', '<', $toDate)
                    ->andReturnSelf();

                $closure($subQuery);

                return true;
            }))
            ->andReturnSelf();
        $mockQuery->shouldReceive('get')
            ->andReturn(new Collection([new Subscriber(), new Subscriber()]));

        $repository = Mockery::mock(SubscriberRepository::class)->makePartial();
        $repository->shouldReceive('getQuery')
            ->andReturn($mockQuery);

        $result = $repository->getNotEmailedSubscribers($toDate);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $mockQuery->shouldHaveReceived('where')->once();
        $mockQuery->shouldHaveReceived('get')->once();
    }
}
