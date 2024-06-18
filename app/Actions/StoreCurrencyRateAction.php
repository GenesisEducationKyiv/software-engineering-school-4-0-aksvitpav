<?php

namespace App\Actions;

use App\DTOs\CurrencyRateDTO;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StoreCurrencyRateAction
{
    /**
     * @param CurrencyRateRepositoryInterface $currencyRateRepository
     */
    public function __construct(
        protected CurrencyRateRepositoryInterface $currencyRateRepository,
    ) {
    }

    /**
     * @param CurrencyRateDTO $dto
     * @return Model|CurrencyRate|null
     */
    public function execute(CurrencyRateDTO $dto): Model|CurrencyRate|null
    {
        /** @var CurrencyRate|null $lastRate */
        $lastRate = $this->currencyRateRepository->findBy(
            [
                'currency_code' => $dto->getCurrencyCode()
            ],
            'fetched_at',
            false
        );

        if (
            ! $lastRate
            || $this->isRatesChanged($lastRate, $dto)
            || $this->isRatesOlderThan($lastRate, now()->subHour())
        ) {
            return $this->currencyRateRepository->create($dto->toArray());
        }

        return $this->currencyRateRepository->updateById(
            $lastRate->id,
            [
                'fetched_at' => $dto->getFetchedAt()
            ]
        );
    }

    private function isRatesChanged(CurrencyRate $lastRate, CurrencyRateDTO $dto): bool
    {
        return $lastRate->buy_rate !== $dto->getBuyRate()
            || $lastRate->sale_rate !== $dto->getSaleRate();
    }

    public function isRatesOlderThan(CurrencyRate $lastRate, Carbon $olderThan): bool
    {
        return $lastRate->fetched_at < $olderThan;
    }
}
