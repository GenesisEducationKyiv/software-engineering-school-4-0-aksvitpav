<?php

namespace App\Actions;

use App\DTOs\CurrencyRateDTO;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Model;

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
            $lastRate?->buy_rate === $dto->getBuyRate()
            && $lastRate->sale_rate === $dto->getSaleRate()
        ) {
            return $this->currencyRateRepository->updateById(
                $lastRate->id,
                [
                    'fetched_at' => $dto->getFetchedAt()
                ]
            );
        }

        if (
            $lastRate?->buy_rate !== $dto->getBuyRate()
            || $lastRate->sale_rate !== $dto->getSaleRate()
        ) {
            return $this->currencyRateRepository->create($dto->toArray());
        }

        if ($lastRate->fetched_at < now()->subHour()) {
            return $this->currencyRateRepository->create($dto->toArray());
        }

        return $lastRate;
    }
}
