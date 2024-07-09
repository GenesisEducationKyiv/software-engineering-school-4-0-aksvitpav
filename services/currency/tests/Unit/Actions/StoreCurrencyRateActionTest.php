<?php

namespace Tests\Unit\Actions;

use App\Actions\StoreCurrencyRateAction;
use App\DTOs\CurrencyRateDTO;
use App\Enums\CurrencyCodeEnum;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreCurrencyRateActionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_stores_a_new_currency_rate()
    {
        $dto = new CurrencyRateDTO(
            CurrencyCodeEnum::USD->value,
            50.05,
            50.10,
            now(),
        );

        $mockRepo = $this->createMock(CurrencyRateRepositoryInterface::class);
        $mockRepo->expects($this->once())
            ->method('create')
            ->with($dto->toArray())
            ->willReturn(new CurrencyRate($dto->toArray()));

        $action = new StoreCurrencyRateAction($mockRepo);
        $result = $action->execute($dto);

        $this->assertInstanceOf(CurrencyRate::class, $result);
        $this->assertEquals($dto->getCurrencyCode(), $result->currency_code->value);
        $this->assertEquals($dto->getBuyRate(), $result->buy_rate);
        $this->assertEquals($dto->getSaleRate(), $result->sale_rate);
        $this->assertEquals($dto->getFetchedAt()->toDateString(), $result->fetched_at->toDateString());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_create_new_rate_if_rates_changed()
    {
        $existingRate = CurrencyRate::query()->create([
            'currency_code' => 'EUR',
            'buy_rate' => 50.15,
            'sale_rate' => 50.20,
            'fetched_at' => now()->subHour(),
        ]);

        $newRate = new CurrencyRateDTO(
            CurrencyCodeEnum::USD->value,
            50.18,
            50.22,
            now(),
        );

        $mockRepo = $this->createMock(CurrencyRateRepositoryInterface::class);
        $mockRepo->expects($this->once())
            ->method('findBy')
            ->with([
                'currency_code' => $newRate->getCurrencyCode(),
            ], 'fetched_at', false)
            ->willReturn($existingRate);

        $mockRepo->expects($this->once())
            ->method('create')
            ->with($newRate->toArray())
            ->willReturn(new CurrencyRate($newRate->toArray()));

        $action = new StoreCurrencyRateAction($mockRepo);
        $result = $action->execute($newRate);

        $this->assertInstanceOf(CurrencyRate::class, $result);
        $this->assertNotEquals($existingRate->id, $result->id);
        $this->assertEquals($newRate->getCurrencyCode(), $result->currency_code->value);
        $this->assertEquals($newRate->getBuyRate(), $result->buy_rate);
        $this->assertEquals($newRate->getSaleRate(), $result->sale_rate);
        $this->assertEquals($newRate->getFetchedAt()->toDateString(), $result->fetched_at->toDateString());
    }

    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function test_it_updates_fetched_at_if_rates_same_and_within_an_hour()
    {
        $existingRate = CurrencyRate::query()->create([
            'currency_code' => CurrencyCodeEnum::USD->value,
            'buy_rate' => 50.05,
            'sale_rate' => 50.10,
            'fetched_at' => now()->subMinute(30),
        ]);

        $newRate = new CurrencyRateDTO(
            CurrencyCodeEnum::USD->value,
            50.05,
            50.10,
            now(),
        );

        $mockRepo = $this->createMock(CurrencyRateRepositoryInterface::class);
        $mockRepo->expects($this->once())
            ->method('findBy')
            ->with([
                'currency_code' => $newRate->getCurrencyCode(),
            ], 'fetched_at', false)
            ->willReturn($existingRate);

        $mockRepo->expects($this->once())
            ->method('updateById')
            ->with($existingRate->id, [
                'fetched_at' => $newRate->getFetchedAt(),
            ])
            ->willReturn($existingRate);

        $action = new StoreCurrencyRateAction($mockRepo);
        $result = $action->execute($newRate);

        $this->assertInstanceOf(CurrencyRate::class, $result);
        $this->assertEquals($existingRate->id, $result->id);
        $this->assertEquals($newRate->getCurrencyCode(), $result->currency_code->value);
        $this->assertEquals($newRate->getBuyRate(), $result->buy_rate);
        $this->assertEquals($newRate->getSaleRate(), $result->sale_rate);
        $this->assertEquals($newRate->getFetchedAt()->toDateString(), $result->fetched_at->toDateString());
    }
}
