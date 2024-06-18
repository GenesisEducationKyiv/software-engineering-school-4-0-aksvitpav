<?php

namespace Tests\Unit\Repositories;

use App\Models\CurrencyRate;
use App\Repositories\CurrencyRateRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Mockery;
use Tests\TestCase;

class CurrencyRateRepositoryTest extends TestCase
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
        $repository = new CurrencyRateRepository();
        $model = $repository->getModel();

        $this->assertInstanceOf(CurrencyRate::class, $model);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_get_query()
    {
        $repository = new CurrencyRateRepository();
        $query = $repository->getQuery();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $query);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_clear_values_older_than()
    {
        $date = Carbon::now()->subDays(30);

        $mockQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $mockQuery->shouldReceive('where')
            ->with('fetched_at', '<=', $date)
            ->andReturnSelf();
        $mockQuery->shouldReceive('delete')
            ->andReturn(1);

        $repository = Mockery::mock(CurrencyRateRepository::class)->makePartial();
        $repository->shouldReceive('getQuery')
            ->andReturn($mockQuery);

        $repository->clearValuesOlderThan($date);

        $mockQuery->shouldHaveReceived('where')->with('fetched_at', '<=', $date)->once();
        $mockQuery->shouldHaveReceived('delete')->once();

        $this->assertTrue(true);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_clear_values_older_than_with_no_records()
    {
        $date = Carbon::now()->subDays(30);

        $mockQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $mockQuery->shouldReceive('where')
            ->with('fetched_at', '<=', $date)
            ->andReturnSelf();
        $mockQuery->shouldReceive('delete')
            ->andReturn(0);

        $repository = Mockery::mock(CurrencyRateRepository::class)->makePartial();
        $repository->shouldReceive('getQuery')
            ->andReturn($mockQuery);

        $repository->clearValuesOlderThan($date);

        $mockQuery->shouldHaveReceived('where')->with('fetched_at', '<=', $date)->once();
        $mockQuery->shouldHaveReceived('delete')->once();

        $this->assertTrue(true);
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_clear_values_older_than_with_future_date()
    {
        $date = Carbon::now()->addDays(30);

        $mockQuery = Mockery::mock(\Illuminate\Database\Eloquent\Builder::class);
        $mockQuery->shouldReceive('where')
            ->with('fetched_at', '<=', $date)
            ->andReturnSelf();
        $mockQuery->shouldReceive('delete')
            ->andReturn(5);

        $repository = Mockery::mock(CurrencyRateRepository::class)->makePartial();
        $repository->shouldReceive('getQuery')
            ->andReturn($mockQuery);

        $repository->clearValuesOlderThan($date);

        $mockQuery->shouldHaveReceived('where')->with('fetched_at', '<=', $date)->once();
        $mockQuery->shouldHaveReceived('delete')->once();

        $this->assertTrue(true);
    }
}
